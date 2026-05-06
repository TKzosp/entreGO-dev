@extends('layouts.app')

@section('title', 'FAQ')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-slate-900 mb-2">Perguntas Frequentes</h1>
        <p class="text-slate-500 mb-8">Encontre respostas rápidas sobre o uso do sistema entreGO.</p>

        <div class="space-y-4">
            @foreach ($faqs as $faq)
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
                    <h2 class="text-lg font-semibold text-slate-800">{{ $faq['pergunta'] }}</h2>
                    <p class="text-slate-600 mt-2">{{ $faq['resposta'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection