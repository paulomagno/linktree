@extends('admin.template');

@section('title','Devs Bio - Home');

@section('content')
   <header>
       <h2>Suas Páginas</h2>
   </header>

   <table>
       <thead>
           <tr>
               <th>Título</th>
               <th>Ações</th>
            </tr>
       </thead>
       <tbody>
           @foreach ($pages as $page)
                <tr>
                    <td>{{$page->op_title}} ({{$page->slug}})</td>
                    <td>
                        <a href="{{url('/'.$page->slug)}}" target="_blank"> Abrir</a>
                        <a href="{{url('/admin/'.$page->slug.'/links')}}" > Links</a>
                        <a href="{{url('/'.$page->slug.'/design')}}" > Aparência</a>
                        <a href="{{url('/'.$page->slug.'/stats')}}" > Estatísticas</a>
                    </td>
                </tr>
           @endforeach
       </tbody>
   </table>
@endsection
