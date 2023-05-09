<a href="{{ $url }}" {{ empty($attributes['class']) || preg_match('/(\s|^)btn(\s|$)/', $attributes['class']) === 0 ? $attributes->merge(["class"=>"btn "]) : $attributes }}>{{ $slot }}</a>
