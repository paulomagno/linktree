@extends('admin.page')

@section('body')

    <h3>{{isset($link)? 'Editar Link' : 'Novo Link'}}</h3>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
    @endif

    <form  method="post">
        @csrf

        <label for="status">
                Status : <br>

                <select name="status" id="status" >
                    <option {{isset($link)? ($link->status == '1' ? 'selected' : '') : ''}} value="1">
                        Ativo
                    </option>
                    <option {{isset($link)? ($link->status == '0' ? 'selected' : '') : ''}}  value="0">
                        Desativado
                    </option>
                </select>
        </label>

        <label for="title">
            Título do Link:<br/>
            <input type="text" name="title" id="title" value="{{$link->title ?? '' }}">
        </label>

        <label for="href">
            URL do Link:<br/>
            <input type="text" name="href" id="href"  value="{{$link->href ?? '' }}">
        </label>

        <label for="op_bg_color">
            Cor do Fundo:<br/>
            <input type="color" name="op_bg_color" id="op_bg_color" value="{{$link->op_bg_color ?? '#FFFFFF' }}">
        </label>

        <label for="op_text_color">
            Cor do Texto:<br/>
            <input type="color" name="op_text_color" id="op_text_color" value="{{$link->op_text_color ?? '#000000' }}">
        </label>

        <label for="op_border_type">
            Tipo de borda : <br>

            <select name="op_border_type" id="op_border_type" >
                <option {{isset($link)? ($link->op_border_type == 'square' ? 'selected' : '') : ''}} value="square">
                    Quadrada
                </option>
                <option {{isset($link)? ($link->op_border_type == 'rounded' ? 'selected' : '') : ''}} value="rounded">
                    Arredondada
                 </option>
            </select>
        </label>

        <label>
            <input type="submit" value="Salvar" />
        </label>
    </form>
@endsection
