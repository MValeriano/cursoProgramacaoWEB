<?php

$total = 0; // Variável acumuladora para somar os valores

while (true) { // Loop infinito (vamos quebrar com break)
    
    // Pede o valor do produto
    echo "Digite o valor do produto (0 para finalizar): ";
    $valor = floatval(trim(fgets(STDIN))); // Lê e converte para número

    // Se o valor for 0, sai do loop
    if ($valor == 0) {
        break;
    }

    // Soma o valor ao total
    $total += $valor;
}

// Exibe o total da compra
echo "Total da compra: R$ " . number_format($total, 2, ',', '.') . "\n";
