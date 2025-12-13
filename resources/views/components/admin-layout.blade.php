@props(['title' => 'Admin', 'header' => 'ড্যাশবোর্ড'])
@include('layouts.admin', ['title' => $title, 'header' => $header, 'slot' => $slot])
