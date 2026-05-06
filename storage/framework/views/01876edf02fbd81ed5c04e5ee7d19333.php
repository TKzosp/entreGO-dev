<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'type' => 'submit',
    'loadingText' => null,
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
    'type' => 'submit',
    'loadingText' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<button
    type="<?php echo e($type); ?>"
    x-data="{ loading: false }"
    <?php if($type === 'submit'): ?>
        @click="if ($el.form && $el.form.checkValidity()) loading = true"
    <?php endif; ?>
    :disabled="loading"
    :aria-busy="loading"
    <?php echo e($attributes->merge([
        'class' => 'inline-flex items-center justify-center gap-2 min-h-[44px] px-5 py-2.5 bg-entrego-blue border border-transparent rounded-lg font-medium text-sm text-white hover:bg-entrego-blue-600 focus:outline-none focus:ring-2 focus:ring-entrego-blue focus:ring-offset-2 active:bg-entrego-blue-700 disabled:opacity-70 disabled:cursor-not-allowed transition-colors duration-150'
    ])); ?>

>
    
    <svg x-show="loading" x-cloak class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24" aria-hidden="true">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor"
              d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
    </svg>

    <span x-show="!loading"><?php echo e($slot); ?></span>
    <span x-show="loading" x-cloak><?php echo e($loadingText ?? 'Enviando...'); ?></span>
</button>
<?php /**PATH C:\Users\rafae\Downloads\pin\entreGO-dev-main-fixed\entreGO-dev-main\resources\views/components/primary-button.blade.php ENDPATH**/ ?>