@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500/30 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100 dark:focus:ring-indigo-500/20 transition-all duration-200']) }}>
