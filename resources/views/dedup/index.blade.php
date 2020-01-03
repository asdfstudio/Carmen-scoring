@extends('layouts.simple')

@section('content-header')
  <h1>Database Duplicate Management</h1>
@endsection


@section('content')
  
  <div style="padding: 0 0 0 40px;">
    
    <h2>People</h2>
    
    <div style="padding: 10px 0 10px 40px;">
      
      <!--
      <h4><a href="{{ route('dedup.dup_list') }}">Browse Duplicates</a></h4>
      
      <p>View a list of people in the database with info about duplicates.</p>
      
      <hr>
      -->
      
      <h4><a href="{{ route('dedup.convert_person_type_choir') }}">Convert Person, Type, &amp; Choir Relationships</a></h4>
      
      <p>On this page, you can run a script to convert person, type, and choir relationships to the new database table structure.  This is needed in order to allow many-to-many relationships between the data.</p>
      
      <hr>
      
      <h4><a href="{{ route('dedup.merge_dups') }}">Merge Duplicates Automatically</a></h4>
      
      <p>On this page, you can run a script to merge duplicate person records based on email and name/school association.</p>
      
      <hr>
      
      <h4><a href="{{ route('dedup.merge_dups_manual') }}">Merge Duplicates Manually</a></h4>
      
      <p>On this page, you can review potential duplicates that have the same name, but which do not contain enough other information to confirm the duplicate.  You can then manually choose which ones to merge.</p>
      
      <hr>
      
      <h4><a href="{{ route('dedup.delete_blanks') }}">Delete Blank Records</a></h4>
      
      <p>This page will go through the <code>people</code> table in the database and delete all the blank records.  (Blank records may have been added by a previous bug in certain forms.)</p>
      
      <hr>
      
      <p><strong>NOTE:</strong> If you get a QueryException when using the Convert or Merge scripts above, then run the following commands:</p>
      <p><code>php artisan migrate</code></p>
      <p><code>php artisan db:seed --class TypesTableSeeder</code></p>
      
      <hr>
      
    </div>
    
  </div>
  
  <div style="padding: 0 0 0 40px;">
    
    <h2>Schools</h2>
    
    <div style="padding: 10px 0 10px 40px;">
      
      <!--
      <h4><a href="{{ route('dedup.dup_list_schools') }}">Browse Duplicates</a></h4>
      
      <p>View a list of schools in the database with info about duplicates.</p>
      
      <hr>
      -->
      
      <h4><a href="{{ route('dedup.merge_dup_schools_manual') }}">Merge Duplicates Manually</a></h4>
      
      <p>Review potential duplicate schools that have the same name or location, then manually choose which ones to merge.</p>
      
      <hr>
      
    </div>
    
  </div>
  
  <div style="padding: 0 0 0 40px;">
    
    <h2>Choirs</h2>
    
    <div style="padding: 10px 0 10px 40px;">
      
      <h4><a href="{{ route('dedup.merge_dup_choirs_manual') }}">Merge Duplicates Manually</a></h4>
      
      <p>Review potential duplicate choirs that have the same name or school, then manually choose which ones to merge.</p>
      
      <hr>
    </div>
    
  </div>
  
@endsection
