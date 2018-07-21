@extends('layouts.sidebar')
@section('content')

<ul class="breadcrumbs">
  <li><a href="{{ URL::to('home') }}">หน้าหลัก</a></li>
  <li class="unavailable"><a href="#">ข้อมูลพื้นฐาน</a></li>
  <li class="current"><a href="#">จัดการเรียงลำดับฝ่าย</a></li>
</ul>

<fieldset>
  <h2>จัดการเรียงลำดับฝ่าย</h2> 
  <div class="">
    <div class="medium-12 columns">
        
      <table> 
      <tbody> 
      @foreach( $dep as $a )
        <tr>
          <td>{{ $a->departmentName }}</td>
          <td>
            <input name="sort_number{{ $a->department_id }}" id="sort_number{{ $a->department_id }}" type="text" placeholder="เลขลำดับ" value="{{ $a->sort }}" >
          </td>
          <td>
             <a title="บันทึกข้อมูล" href="#" onclick="editsort( {{ $a->department_id }} )" class="small button" >บันทึก</a> 
          </td>
        </tr>
      @endforeach  
      </tbody> 
      </table>
            
    </div>
  </div>	
</fieldset>
@stop