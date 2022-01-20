@component('mail::message')

<h2>Bookings Found</h2>
@foreach($slots as $slot)
    {{ $slot->dateTime->toDayDateTimeString() }} <br>
@endforeach

@component('mail::button', ['url' => 'https://book.getslick.com/#/salon/2650'])
Book Now!
@endcomponent
