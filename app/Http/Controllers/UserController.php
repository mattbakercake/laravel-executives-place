<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Currency;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Services\Rates;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;

class UserController extends Controller
{

    private $rateService;

    public function __construct(Rates $rateService)
    {
        $this->rateService = $rateService; //inject rate service into controller
    }

    /**
     * Display a listing of the resource.
     * 
     * @return json array of user objects
     */
    public function index()
    {
        return new UserCollection(User::all());
    }


    /**
     * Save new user
     * 
     * @return json copy of user data object if successful otherwise 'Error'
     */
    public function store(Request $request)
    {
        $user = new User;

        //validate new user details
        $request->validate($this->validationRules($user));

        //validation passed - save to db
        $user->fill($request->all());
        $user->password = Str::password();
        $result = $user->save();

        if ($result) {
            $user->refresh();
            return response(new UserResource($user), 201);
        } else {
            return response()->json(['Error', 500]);
        }
    }

    /**
     * Display the specified resource.
     * 
     * @return json user object 
     */
    public function show(string $id, string $conversion_iso = null)
    {
        $user = User::findOrFail($id);
        $conversion_iso = strtoupper($conversion_iso);

        //no currency supplied OR matches - just return user's default rate
        if (!$conversion_iso || $conversion_iso === $user->currency_iso) {
            return new UserResource($user);
        }

        //convert user rate details
        try {
            $user->rate_hour = $this->convertRate($user->rate_hour, $user->currency_iso, $conversion_iso);
            $user->currency_iso = $conversion_iso;

            return new UserResource($user);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getCode(), 'message' => $e->getMessage()]);
        }
    }


    /**
     * Update the specified resource in storage.
     * 
     * @return json user object if successful or error
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        //validate new user details
        $request->validate($this->validationRules($user));

        //validation passed - update user
        $user->fill($request->all());
        $result = $user->save();

        if ($result) {
            $user->refresh();
            return response(new UserResource($user), 200);
        } else {
            return response()->json(['Error', 500]);
        }
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @return json nothing if successful or error
     */
    public function destroy(string $id)
    {
        $result = User::destroy($id);
        if ($result) {
            return response()->json(null, 204);
        } else {
            return response()->json(['Error', 500]);
        }
    }

    /**
     * validation rules for store and update
     * 
     * @return array validation rules
     */
    private function validationRules(User $user)
    {
        //update rules
        if ($user->id) {
            return [
                'email' => ['email', Rule::unique('users')->ignore($user->id)],
                'rate_hour' => ['integer'],
                'currency_iso' => [Rule::in(Currency::pluck('iso_code')->toArray())]
            ];
        }
        //new user rules
        return [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'rate_hour' => ['required', 'integer'],
            'currency_iso' => ['required', Rule::in(Currency::pluck('iso_code')->toArray())]
        ];
    }

    /**
     * converted rate calculation
     * 
     * @return float converted rate value
     */
    private function convertRate($rate, $base_iso, $rate_iso)
    {
        $conversion_rate = $this->rateService->fetchRate($base_iso, $rate_iso);

        return (float)$rate * $conversion_rate;
    }
}
