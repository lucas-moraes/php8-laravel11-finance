export function FormatNumberToCurrency(number) {
  const formatedValue = new Intl.NumberFormat('pt-BR', {
    style: 'currency',
    currency: 'BRL',
  }).format(number);

  return formatedValue;
}
