<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\InventoryModel;

class InventoryController extends BaseController
{
    public function inventory()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $inventory = new InventoryModel();
        $search = request()->getGet('search') ?? '';
        $sort = request()->getGet('sort') ?? 'asc';
        
        // Validate sort parameter
        if (!in_array($sort, ['asc', 'desc'])) {
            $sort = 'asc';
        }

        if ($search) {
            $data['inventory'] = $inventory
                ->groupStart()
                ->like('item_name', $search)
                ->orLike('item_id', $search)
                ->orLike('category', $search)
                ->groupEnd()
                ->orderBy('item_id', $sort)
                ->findAll();
        } else {
            $data['inventory'] = $inventory->orderBy('item_id', $sort)->findAll();
        }

        return view('Inventory/inventory', $data);
    }

    public function add_inventory()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        return view('Inventory/add_inventory');
    }

    public function store_inventory()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $inventory = new InventoryModel();

        $item_name = request()->getPost('item_name');
        
        $exist = $inventory->where('item_name', $item_name)->first();

        if($exist){
            return redirect()->to('/inventory/add')->with('message', 'Item Already Exists');
        }

        $data = [
            'item_name' => $item_name,
            'category' => request()->getPost('category'),
            'quantity' => request()->getPost('quantity'),
            'unit' => request()->getPost('unit'),
            'expiry_date' => request()->getPost('expiry_date') ?: null,
            'description' => request()->getPost('description'),
            'added_by' => session()->get('user_id'),
        ];

        $inventory->insert($data);

        return redirect()->to('/inventory/add')->with('message', 'Item Added Successfully');
    }

    public function edit_inventory($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $inventory = new InventoryModel();

        $data['item'] = $inventory->find($id);

        if(!$data['item']){
            return redirect()->to('/inventory')->with('message', 'Error: Item not found');
        }

        return view('Inventory/edit_inventory', $data);
    }

    public function update_inventory($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $inventory = new InventoryModel();

        $data = [
            'item_name' => request()->getPost('item_name'),
            'category' => request()->getPost('category'),
            'quantity' => request()->getPost('quantity'),
            'unit' => request()->getPost('unit'),
            'expiry_date' => request()->getPost('expiry_date') ?: null,
            'description' => request()->getPost('description'),
        ];

        $inventory->update($id, $data);

        return redirect()->to('/inventory/edit/'.$id)->with('message', 'Item Updated Successfully');
    }

    public function delete_inventory($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $inventory = new InventoryModel();
        
        $exist = $inventory->find($id);
        if(!$exist){
            return redirect()->to('/inventory')->with('message', 'Error: Item not found');
        }

        $inventory->delete($id);

        return redirect()->to('/inventory')->with('message', 'Item #' . $id . ' Deleted Successfully');
    }
}
