@component('mail::message')
<b>{{ $data->date ?? ''}}</b> [Date] <br>
<b>{{ $data->name ?? ''}}</b> [First Name] <br>
<b>{{ $data->last_name ?? ''}} </b> [Last Name] <br>
<b>{{ $data->email ?? '' }}</b> [Email]<br>
<b>{{ $data->phone ?? '' }}</b> [Phone]<br>
<b>{{ $data->continent ?? '' }}</b> [Continent]<br>
<b>{{ $data->country }}</b> [Country]<br>
<b>{{ $data->city ?? '' }}</b> [City]<br>
<b>{{ $data->grade ?? '' }}</b> [Grade]<br>
<b>{{ $data->points ?? '' }}</b> [Lead Points]<br>
<b>{{ $data->university ?? '' }}</b> [University]<br>
<b>{{ $data->discipline ?? '' }}</b> [Discipline]<br>
<b>{{ $data->speciality ?? '' }}</b> [Speciality]<br>
<b>{{ $data->program ?? '' }}</b> [Program]<br>
<b>{{ $data->start_time ?? '' }}</b> [Program Time]<br>
<b>{{ $data->uni_location ?? '' }}</b> [Uni Location]<br>
<b>{{ $data->educational_level ?? '' }}</b> [Educational Level]<br>
<b>{{ $data->funding ?? '' }}</b> [Funding]<br>
<b>{{ $data->social_profile_url ?? '' }}</b> [Social Profile URL]<br>
<b>{{ $data->questions ?? '' }}</b>[Questions]<br>
<b>{{ $data->tracking ?? '' }}</b> [Tracking]<br>
<b>{{ $data->lead_type ?? '' }}</b> [Lead Type]<br>
<b>{{ $data->representative ?? '' }}</b> [Representative]<br>
<b>{{ $data->landing_page ?? '' }}</b> [Landing page]<br>
<b>{{ $data->conversion_page }}</b> [Conversion page]<br>
<b>{{ $data->ga_client_id ?? '' }}</b> [GA client_id]<br>
<b>{{ $data->vip ?? '' }}</b> [VIP]<br>
<br>
<b>Sophia's fields</b><br>
Nationality: {{ $data->nationality ?? '' }} <br>
Started application: {{ $data->started_application ?? '' }} <br>
Applied Unis: {{$data->applied_unis ?? ''}} <br>
Budget: {{ $data->budget ?? '' }}<br>
Programme level: {{ $data->programme_level ?? '' }}
<br>
Pipedrive lead: <a href="https://eduopinions.pipedrive.com/deal/{{ $deal->id }}">https://eduopinions.pipedrive.com/deal/{{ $deal->id }}</a>
@endcomponent
