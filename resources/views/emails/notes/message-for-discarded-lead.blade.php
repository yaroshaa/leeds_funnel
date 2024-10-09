@component('mail::message')
Hello {{ $data['name'] }},

Thank you for contacting us regarding your studies research. EDUopinions helps students find the ideal programme. At present, we can only offer personalised service to students wishing to pursue Business & Management studies in Europe.

Are you interested in Business & Management or other relevant studies in Europe? If yes, <a href="https://www.eduopinions.com/find-the-best-university-for-you/#where-to-study" target="_blank">feel free to fill this form</a> and our team will contact you as soon as possible.

Best wishes,

EDUopinions Team

<img src="{{ url('/images/logo.png') }}" style="width: 180px; height: auto" alt="Real Student Reviews">
@endcomponent
