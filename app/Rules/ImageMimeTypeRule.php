<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ImageMimeTypeRule implements Rule
{
  /**
   * Create a new rule instance.
   *
   * @return void
   */
  public function __construct()
  {
    //
  }

  /**
   * Determine if the validation rule passes.
   *
   * @param  string  $attribute
   * @param  mixed  $value
   * @return bool
   */
  public function passes($attribute, $value)
  {
    $image = $value;

    $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg', 'gif');
    $fileExtension = $image->getClientOriginalExtension();

    if (in_array($fileExtension, $allowedExtensions)) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * Get the validation error message.
   *
   * @return string
   */
  public function message()
  {
    return 'Only .jpg, .jpeg, .png, .svg and .gif file is allowed.';
  }
}
