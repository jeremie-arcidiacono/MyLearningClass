{{--
A customisable form input component (style 2 : rbt style).

Need to provide :
    - $name : the name of the input
    - $type : (optional) the type of the input (text, email, password, etc.). Default is text.
    - $required : (optional) if the input is required or not. Default is true.
    - $sticky : (optional) if the input value should be sticky or not. Default is true.
    - $otherAttributes : (optional) an array of other attributes to add to the input.
                          e.g : 'class="my-class" maxlength="10"'
--}}

@php
    $type = $type ?? 'text';
    $required = $required ?? true;
    $sticky = $sticky ?? true;

    if ($sticky) {
        $value = $old[$name] ?? '';
    } else {
        $value = '';
    }

    // Display error for this input
    if (isset($errors[$name])) {
        $invalidClass = 'input-error';
    }
    else {
        $invalidClass = '';
    }
@endphp

<div class="rbt-form-group @if($value !== '') focused @endif"> {{-- Focused to avoid animation glitch --}}
    <label for="{{ $name }}">{!! $slot !!}</label>
    <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $name }}"
            value="{{ $value }}"
            {!! $required ? 'required' : '' !!}
            {!! $otherAttributes ?? '' !!}
            class="{{ $invalidClass }}">
    <span class="focus-border"></span>
</div>
