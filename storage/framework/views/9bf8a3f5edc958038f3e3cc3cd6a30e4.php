<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'disabled' => false,
    'hasError' => false,
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
    'disabled' => false,
    'hasError' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $base = 'block w-full rounded-md shadow-sm text-sm px-3 py-2.5 transition-colors duration-150 placeholder:text-gray-400 disabled:opacity-60 disabled:cursor-not-allowed';

    $state = $hasError
        ? 'border-red-500 text-red-900 focus:border-red-500 focus:ring-red-500'
        : 'border-gray-300 focus:border-entrego-blue focus:ring-entrego-blue';
?>

<input
    <?php if($disabled): echo 'disabled'; endif; ?>
    <?php if($hasError): ?> aria-invalid="true" <?php endif; ?>
    <?php echo e($attributes->merge(['class' => "$base $state"])); ?>

>
<?php /**PATH C:\Users\rafae\Downloads\pin\entreGO-dev-main-fixed\entreGO-dev-main\resources\views/components/text-input.blade.php ENDPATH**/ ?>