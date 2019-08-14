@extends('layouts.simple')

@section('content-header')
  <h1>Database Duplicate Management</h1>
@endsection


@section('content')
  
  <h4><a href="{{ route('admin.dedup.dup_list') }}">Browse Duplicates</a></h4>
  
  <p>View a list of people in the database with info about duplicates.</p>

  <hr>

  <h4><a href="{{ route('admin.dedup.convert_person_type_choir') }}">Convert Person, Type, &amp; Choir Relationships</a></h4>
  
  <p>On this page, you can run a script to convert person, type, and choir relationships to the new database table structure.  This is needed in order to allow many-to-many relationships between the data.</p>

  <hr>

  <h4><a href="{{ route('admin.dedup.merge_dups') }}">Merge Duplicates</a></h4>
  
  <p>On this page, you can run a script to merge duplicate person records.</p>

@endsection
