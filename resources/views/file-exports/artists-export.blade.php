<html>
<table>
    <thead>
        <tr>
            <th>{{ trans('SL') }}</th>
            <th>{{ trans('name') }}</th>
            <th>{{ trans('email') }}</th>
            <th>{{ trans('date') }}</th>
            <th>{{ trans('description') }}</th>
            <th>{{ trans('website') }} </th>
            <th>{{ trans('image_path') }} </th>
        </tr>
    </thead>

    <tbody>
        @foreach ($data['artists'] as $key => $item)
            <tr>
                <td>
                    {{ ++ $key }}
                </td>
                <td>
                    {{ $item['name'] }}
                </td>
                <td>
                    {{ $item['email'] }}
                </td>
                <td>
                    {{ $item['artist_date'] }}
                </td>
                <td>
                    {{ $item['description'] }}
                </td>
                <td>
                    {{ $item['website'] }}
                </td>
                <td>
                    {{ $item['image_path'] }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

</html>
