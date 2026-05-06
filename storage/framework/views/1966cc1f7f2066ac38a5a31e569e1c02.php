<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'value' => null,
    'required' => false,
]));

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

foreach (array_filter(([
    'value' => null,
    'required' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<label <?php echo e($attributes->merge(['class' => 'block font-medium text-sm text-gray-700 mb-1'])); ?>>
    <?php echo e($value ?? $slot); ?>

    <?php if($required): ?>
        <span class="text-red-500" aria-hidden="true">*</span>
        <span class="sr-only">obrigatório</span>
    <?php endif; ?>
</label>
<?php /**PATH C:\Users\rafae\Downloads\pin\entreGO-dev-main-fixed\entreGO-dev-main\resources\views/components/input-label.blade.php ENDPATH**/ ?>