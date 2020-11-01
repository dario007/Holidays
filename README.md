# Holidays
Extendable Holiday API

HOW IT WORKS


new BalkanHolidays(string 'year', boolean show_only_date, string 'state or all');

Usage Example:

get all: 

$blagdani = new BalkanHolidays('2022', false, 'all');<br>
$blagdani->getHolidays();<br>
<br>

get specific state:

$blagdani = new BalkanHolidays('2022', false, 'croatia');<br>
$blagdani->getHolidays();<br>