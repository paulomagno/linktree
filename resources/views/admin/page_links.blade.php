@extends('admin.page')

@section('body')
    <a class="bigbutton" href="{{url('/admin/'.$page->slug.'/newlink')}}">Novo Link</a>
    <ul id="links">
        @foreach ($links as $link)
            <li class="link--item" data-id="{{$link->id}}">
                <div class="link--item-order">
                    <img src="{{url('/assets/images/sort.png')}}" alt="Ordenar" width="18" />
                </div>
                <div class="link--item-info">
                    <div class="link--item-title">
                        {{$link->title}}
                    </div>

                    <div class="link--item-href">
                        {{$link->href}}
                    </div>
                </div>
                <div class="link--item-buttons">
                    <a href="{{url('/admin/'.$page->slug.'/editlink/'.$link->id)}}">Editar</a>
                    <a href="{{url('/admin/'.$page->slug.'/dellink/'.$link->id)}}">Excluir</a>
                </div>

            </li>
        @endforeach
    </ul>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.13.0/Sortable.min.js"></script>
    <script>
        new Sortable(document.querySelector('#links'),{
            animation:150,
            onEnd: async (e) => {
                let id = e.item.getAttribute('data-id');
                let link = `{{url('/admin/linkorder/${id}/${e.newIndex}')}}`;
                await fetch(link);
                window.location.href = window.location.href;
            }
        });
    </script>
@endsection
