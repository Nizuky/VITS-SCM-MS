<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['url']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['url']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<tr>
    <td class="header" style="padding: 25px 0; text-align: center;">
        <a href="<?php echo new \Illuminate\Support\EncodedHtmlString($url); ?>" style="display: inline-block;">
            <img src="<?php echo new \Illuminate\Support\EncodedHtmlString(asset('vitslogo.png')); ?>" class="logo" alt="<?php echo new \Illuminate\Support\EncodedHtmlString(config('app.name')); ?>" style="width:120px; height:auto; display:block; margin:0 auto;" />
        </a>
    </td>
</tr>
<?php /**PATH C:\Users\janar\Herd\scms\resources\views/vendor/mail/html/header.blade.php ENDPATH**/ ?>