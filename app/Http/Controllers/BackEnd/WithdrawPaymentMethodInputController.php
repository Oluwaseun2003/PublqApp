<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\WithdrawMethodInput;
use App\Models\WithdrawMethodOption;
use App\Models\WithdrawPaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class WithdrawPaymentMethodInputController extends Controller
{
  public function index(Request $request)
  {
    $id = $request->id;
    $data['payment_method'] = WithdrawPaymentMethod::where('id', $id)->select('name')->firstOrFail();
    $data['inputs'] = WithdrawMethodInput::where('withdraw_payment_method_id', $id)->orderBy('order_number', 'ASC')->get();
    return view('backend.withdraw.form.index', $data);
  }
  //store
  public function store(Request $request)
  {
    $inname = make_input_name($request->label);
    $inputs = WithdrawMethodInput::where('withdraw_payment_method_id', $request->withdraw_payment_method_id)->get();
    $maxOrder = WithdrawMethodInput::where('withdraw_payment_method_id', $request->withdraw_payment_method_id)->max('order_number');

    $messages = [
      'options.*.required_if' => 'Options are required if field type is select dropdown/checkbox',
      'placeholder.required_unless' => 'The placeholder field is required unless field type is Checkbox'
    ];

    $rules = [
      'label' => [
        'required',
        function ($attribute, $value, $fail) use ($inname, $inputs) {
          foreach ($inputs as $key => $input) {
            if ($input->name == $inname) {
              $fail("Input field already exists.");
            }
          }
        },
      ],
      'placeholder' => 'required_unless:type,3',
      'type' => 'required',
      'options.*' => 'required_if:type,2,3'
    ];

    $validator = Validator::make($request->all(), $rules, $messages);
    if ($validator->fails()) {
      $errmsgs = $validator->getMessageBag()->add('error', 'true');
      return response()->json($validator->errors());
    }

    $input = new WithdrawMethodInput;
    $input->withdraw_payment_method_id = $request->withdraw_payment_method_id;
    $input->type = $request->type;
    $input->label = $request->label;
    $input->name = $inname;
    $input->placeholder = $request->placeholder;
    $input->required = $request->required;
    $input->order_number = $maxOrder + 1;
    $input->save();

    if ($request->type == 2 || $request->type == 3) {
      $options = $request->options;
      foreach ($options as $key => $option) {
        $op = new WithdrawMethodOption;
        $op->withdraw_method_input_id = $input->id;
        $op->name = $option;
        $op->save();
      }
    }

    Session::flash('success', 'Added Successfully');

    return response()->json(['status' => 'success'], 200);
  }

  //edit
  public function edit($id)
  {
    $data = [];
    $input = WithdrawMethodInput::find($id);
    $data['input'] = $input;
    $options = WithdrawMethodOption::where('withdraw_method_input_id', $input->id)->get();
    if (!empty($options)) {
      $data['options'] = $options;
      $data['counter'] = count($options);
    }
    return view('backend.withdraw.form.form-edit', $data);
  }
  //update
  public function update(Request $request)
  {
    $inname = make_input_name($request->label);
    $input = WithdrawMethodInput::find($request->input_id);
    $inputs = WithdrawMethodInput::where('withdraw_payment_method_id', $request->withdraw_payment_method_id)->get();

    $messages = [
      'options.required_if' => 'Options are required',
      'placeholder.required_unless' => 'Placeholder is required',
      'label.required_unless' => 'Label is required',
    ];

    $rules = [
      'label' => [
        'required_unless:type,5',
        function ($attribute, $value, $fail) use ($inname, $inputs, $input) {
          foreach ($inputs as $key => $in) {
            if ($in->name == $inname && $inname != $input->name) {
              $fail("Input field already exists.");
            }
          }
        },
      ],
      'placeholder' => 'required_unless:type,3,5',
      'options' => [
        'required_if:type,2,3',
        function ($attribute, $value, $fail) use ($request) {
          if ($request->type == 2 || $request->type == 3) {
            foreach ($request->options as $option) {
              if (empty($option)) {
                $fail('All option fields are required.');
              }
            }
          }
        },
      ]
    ];

    $validator = Validator::make($request->all(), $rules, $messages);
    if ($validator->fails()) {
      $errmsgs = $validator->getMessageBag()->add('error', 'true');
      return response()->json($validator->errors());
    }


    if ($request->type != 5) {
      $input->label = $request->label;
      $input->name = $inname;
    }

    // if input is checkbox then placeholder is not required
    if ($request->type != 3 && $request->type != 5) {
      $input->placeholder = $request->placeholder;
    }
    $input->required = $request->required;

    $input->save();

    if ($request->type == 2 || $request->type == 3) {
      $option_delete = WithdrawMethodOption::where('withdraw_method_input_id', $request->input_id)->get();

      foreach ($option_delete as $value) {
        $value->delete();
      }
      $options = $request->options;
      foreach ($options as $key => $option) {
        $op = new WithdrawMethodOption;
        $op->withdraw_method_input_id   = $input->id;
        $op->name = $option;
        $op->save();
      }
    }

    Session::flash('success', 'Updated Successfully');

    return response()->json(['status' => 'success'], 200);
  }
  //order_update
  public function order_update(Request $request)
  {
    $ids = $request->ids;
    $orders = $request->orders;

    if (!empty($ids)) {
      foreach ($request->ids as $key => $id) {
        $input = WithdrawMethodInput::where('id', $id)->first();
        $input->order_number = $orders["$key"];
        $input->save();
      }
    }
  }
  //get_options
  public function get_options($id)
  {
    $options = WithdrawMethodOption::where('withdraw_method_input_id', $id)->get();
    return $options;
  }
  //delete
  public function delete(Request $request)
  {
    $input = WithdrawMethodInput::find($request->input_id);
    $options = WithdrawMethodOption::where('withdraw_method_input_id', $request->input_id)->get();
    foreach ($options as $option) {
      $option->delete();
    }
    $input->delete();
    Session::flash('success', 'Deleted Successfully');

    return back();
  }
}
