<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:اضافة فرع', ['only' => ['create','store']]);
         $this->middleware('permission:تعديل فرع', ['only' => ['edit','update']]);
         $this->middleware('permission:حذف فرع', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $branches = Branch::all();
        return view('branches.branches')->with('branches' , $branches);
    }

    public function change($id)
    {
        $branches = DB::table('branches')
                    ->select('type')
                    ->where('id' ,'=', $id)
                    ->first();
                
        if($branches->type == '1'){
            $type = '0';
        }
        else{
            $type = '1';
        }

        $values = array('type'=>$type);
        DB::table('branches')->where('id' , $id)->update($values);

        return redirect('branches')->with('flash_message' , 'branches Updated');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('branches.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'branch_name' => 'Required', 
            'added_date' => 'Required', 
            'added_time' => 'Required', 
            
        ]);
        $branches = new Branch([
            'branch_name' => $request->get('branch_name'),  
            'added_date' => $request->get('added_date'), 
            'added_time' => $request->get('added_time'), 
            'type' => '1', 
            
        ]);
        $branches -> save();
        return redirect('branches')->with('sucess' , 'Data Added');
       							
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $branches = Branch::find($id);
        return view('branches.edit')->with('branches' , $branches);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Branch::where('id' , $id)->update([
            'branch_name' => $request->get('branch_name'),
            'added_date' => $request->get('added_date'),
            'added_time' => $request->get('added_time'),
            'type' => $request->get('type')

        ]);
        return redirect('branches')->with('flash_message' , 'branches Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Branch::destroy($id);
        return redirect('branches')->with('success' , 'branch deleted');
    }
}
