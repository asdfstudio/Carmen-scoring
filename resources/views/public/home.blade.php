@extends('layouts.public')

@section('body-content')
  <div class="slider">
    <div class="slider-inner">
      <ul>
        <li class="slide">
          <img src="/images/slide-1.jpg" alt="Contest Image">
          <h1>A new experience</h1>
          <h2>Bring the best adjudication system to your event</h2>
        </li>
        <li class="slide">
          <img src="/images/slide-2.jpg" alt="Contest Image">
          <h1>A Revolution in the Showchoir World</h1>
        </li>

        <li class="slide">
          <img src="/images/slide-3.jpg" alt="Contest Image">
          <h1>Environmentally Friendly</h1>
          <h2>Forget about paper waste</h2>
        </li>
      </ul>
    </div>
  </div>

  <div class="slim-width">
    <ul class="benefits-group">
      <li>
        <img src="/images/prize.png" alt="" class="icon">
        <span class="summary">Fast<br /> Accurate<br /> Results</span>
        <ul>
          <li>No more waiting for manual calculations</li>
          <li>100% computerized for exactness</li>
        </ul>
      </li>
      <li>
        <img src="/images/lock.png" alt="" class="icon">
        <span class="summary">Trusted<br /> Secure<br /> Platform</span>
        <ul>
          <li>Trusted and loved by the best competitions in the USA</li>
          <li>Username and password protected</li>
        </ul>
      </li>
      <li>
        <img src="/images/leaf.png" alt="" class="icon">
        <span class="summary">Small<br /> Carbon<br /> Footprint</span>
        <ul>
          <li>Everything is digital, from the scoresheets to the results</li>
          <li>No more paper waste</li>
        </ul>
      </li>
      <li>
        <img src="/images/scoresheet.png" alt="" class="icon">
        <span class="summary">Custom<br /> Event<br /> Specs</span>
        <ul>
          <li>Keep the traditions of your competition and add the power of Carmen</li>
          <li>Simple and easy to learn</li>
        </ul>
      </li>
      <li>
        <img src="/images/devices-no-flower.png" alt="" class="icon">
        <span class="summary">Anywhere<br /> Any time<br /> Any device</span>
        <ul>
          <li>Compatible with tablets AND laptop computers</li>
          <li>Any competition of any size</li>
        </ul>
      </li>
    </ul>

  </div>

  <div class="slim-width">
    <div class="video-container">
      <span class="heading">Discover exactly what the judges love about Carmen</span>

      <div class="video-wrapper">
        <div class="video">
          <iframe src="https://www.youtube.com/embed/mWfdfZ1e9WE?rel=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>
        </div>

      </div>


    </div>
  </div>

  <div class="slim-width">
    <h2>Carmen Scoring<br /> Works for All Competitions</h2>

    <ul class="event-sizes">
      <li class="event-small">Small<br /> Events</li>
      <li class="event-medium">Medium<br /> Events</li>
      <li class="event-big">Big<br /> Events</li>
    </ul>

    <h2>Carmen Scoring<br /> Works for All Budgets</h2>

    <ul class="packages">
      <li>Carmen<br /> +<br /> Reps<br /> +<br /> Devices</li>
      <li>Carmen<br /> +<br /> Reps</li>
      <li>Carmen</li>
    </ul>
  </div>



  <div class="slim-width">
    <div class="competitions-container" id="competitions">
      @include('public/competitions-list')
    </div>
  </div>


@endsection
