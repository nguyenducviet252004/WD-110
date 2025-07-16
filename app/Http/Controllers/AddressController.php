<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Ship_address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Ship_address::where('user_id', Auth::id())->paginate(2);

        return view('user.address', compact('addresses'));
    }

    public function create()
    {
        return view('user.addresscreate');
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'ship_address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'recipient_name' => 'required|string|max:255',
            'is_default' => Rule::in([0, 1]),
        ]);

        $data['is_default'] = $request->filled('is_default') ? 1 : 0;

        Ship_address::create(array_merge($data, [
            'user_id' => Auth::id(),
        ]));


        return redirect()->route('address.index')->with('success', 'Địa chỉ giao hàng đã được thêm mới thành công.');
    }
    public function setDefault($id)
    {
        $address = Ship_address::findOrFail($id);

        Ship_address::where('user_id', Auth::id())->update(['is_default' => 0]);

        $address->is_default = 1;
        $address->save();

        return back()->with('success', 'Địa chỉ đã được đặt làm mặc định.');
    }
    public function edit($id)
    {
        $address = Ship_address::findOrFail($id);
        return view('user.editAddress', compact('address'));
    }

    // Xử lý cập nhật địa chỉ
    public function update(Request $request, $id)
    {
        $address = Ship_address::findOrFail($id);
        $data = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'ship_address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
        ]);

        $data['is_default'] = $request->has('is_default') ? 1 : 0;

        $address->update($data);

        return back()->with('success', 'Cập nhật địa chỉ thành công.');
    }

    public function destroy(Request $request, $id)
    {
        try {
            // Tìm địa chỉ giao hàng theo ID
            $address = Ship_address::find($id);

            // Kiểm tra nếu không tìm thấy địa chỉ
            if (!$address) {
                return back()->with('error', 'Địa chỉ giao hàng không tồn tại.');
            }

            // Kiểm tra xem có đơn hàng nào sử dụng địa chỉ này không và trạng thái đơn hàng
            $orders = Order::where('ship_address_id', $address->id)
                ->whereIn('status', [0, 1, 2]) // Kiểm tra trạng thái 0, 1, 2
                ->get();

            if ($orders->isNotEmpty()) {
                // Nếu có đơn hàng đang ở trạng thái 0, 1, 2, không cho phép xóa địa chỉ
                return back()->with('error', 'Không thể xóa địa chỉ vì có đơn hàng đang ở trạng thái xử lý, vận chuyển hoặc chờ.');
            }

            // Xóa địa chỉ giao hàng
            $address->delete();

            return back()->with('success', 'Địa chỉ giao hàng đã được xóa thành công.');
        } catch (\Throwable $th) {
            return back()
                ->with('error', 'Có lỗi xảy ra khi xóa địa chỉ: ' . $th->getMessage());
        }
    }
}
