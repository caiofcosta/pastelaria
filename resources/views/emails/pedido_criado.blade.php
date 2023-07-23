<h1>Detalhes do Pedido</h1>
<p>Cliente: {{ $pedido->cliente->name }}</p>
<p>Data do Pedido: {{ \Carbon\Carbon::parse($pedido->created_at)->format('d/m/Y H:i:s') }}</p>


<table style="width: 50%">
    <thead>
        <tr>
            <th>Produto</th>
            <th align="center">Quantidade</th>
            <th align="center">Preço Unitário</th>
            <th align="center">Preço Total</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total_geral = 0;
        @endphp
        @foreach ($pedido['produtos'] as $index => $produto)
            @php
                $total = $produto['preco'] * $produto['quantidade'];
                $total_geral += $total;
            @endphp
            <tr style="background-color: {{ $index % 2 == 0 ? '#f1f1f1' : '##dfdcdc' }}">
                <td align="center">{{ $produto['nome'] }}</td>
                <td align="center">{{ $produto['quantidade'] }}</td>
                <td align="center">{{ $produto['preco'] }}</td>
                <td align="center">R$ {{ number_format($total, 2, ',', '.') }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr style="background-color:#555; color:#fff">
            <td colspan="3">Total Geral:</td>
            <td align="center">R$ {{ number_format($total_geral, 2, ',', '.') }}</td>
        </tr>
    </tfoot>
</table>
