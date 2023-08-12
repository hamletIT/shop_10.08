<?php

namespace App\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Orders;
use App\Services\AdminDarta;
use Illuminate\Routing\Controller as BaseController;

class AdminOrdesUsersController extends BaseController
{
    protected $adminData;

    public function __construct(AdminDarta $adminData)
    {
        $this->adminData = $adminData;
    }
    public function showAllUsers(Request $request)
    {
        $users = User::where('two_factor_secret',null)->paginate(10);

        return view('admin.users',compact('users'));
    }

    public function showAllOrders(Request $request)
    {
        $orders = Orders::with('address_order','users')->paginate(10);

        return view('admin.orders',compact('orders'));
    }

    public function dashboard() 
    {
        return $this->adminData->dashboard();
    }
}