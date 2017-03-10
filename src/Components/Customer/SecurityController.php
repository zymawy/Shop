<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Components\Customer;

use Antvel\Http\Controller;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
	/**
     * The customer repository.
     *
     * @var CustomerRepository
     */
    protected $customer = null;

    /**
     * Creates a new instance.
     *
     * @param CustomersRepository $customer
     * @return void
     */
	public function __construct(CustomersRepository $customer)
    {
        $this->customer = $customer;
    }

	/**
     * Confirms the user's new email address.
     *
     * @param  string $token
     * @param  string $email
     * @return void
     */
    public function confirmEmail(string $token, string $email)
    {
        $petition = (new ChangeEmailRepository())->confirm(
            $user_id = $this->customer->id, $token, $email
        );

        if (! is_null($petition)) {
            $this->customer->update(
                $user_id, ['email' => $email]
            );
        }

        return redirect()->route('customer.index');
    }

    /**
     * Update user's profile with a given action.
     *
     * @param  string $action
     * @param  int|null $customer
     * @return void
     */
    public function update(string $action, $customer = null)
    {
    	$allowed = ['enable', 'disable'];

    	if (! in_array($action, ['enable', 'disable'])) {
    		return $this->respondsWithError('action not allowed');
    	}

    	$action = mb_strtolower($action);

    	$response = $this->customer->$action($customer);

    	if ($response == 'notOk') {
            $message = 'There was an error trying to update your information. Please, Try again!';
        } else {
            $message = 'Your profile has been successfully updated.';
        }

    	return $this->respondsWithSuccess($message);
    }
}
