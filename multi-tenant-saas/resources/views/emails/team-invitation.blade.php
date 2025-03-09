<x-mail::message>
# Team Invitation

You have been invited by {{ $inviter->name }} to join the team "{{ $team->name }}".

<x-mail::button :url="$url">
View Team
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message> 