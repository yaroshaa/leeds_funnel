@component('mail::message')
Hi {{ $data['name'] }},

Following the request for information about your studies, we will soon contact you via WhatsApp with a couple of questions in order to fine-tune the search that we will run for you.

We will contact you on {{ $data['phone'] }}. Is this your WhatsApp number or should we try on a different number?

We offer personalised advice and studies consultation only via WhatsApp.

Best regards,

EDUopinions Team

<img src="{{ url('/images/logo.png') }}" style="width: 180px; height: auto" alt="Real Student Reviews">

@endcomponent
