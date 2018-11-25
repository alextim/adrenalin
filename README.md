## Путешествия
Для использования встроенной формы из самописного плагина переопределите константу в шаблоне путешествия `single-trip.php`.
```
define ('USE_GOOGLE_FORMS', false);
```

## shortcodes

### at_gear_list
Выводит список снаряжения, отсортированный по полю Sort Order из  таксономии "gear_type"

`use` - необязательный параметр для фильтрации продуктов по slug-у терма из таксономии "recommended_use".

Без `use` будут выведены все имеющиеся записи.
```
[at_gear_list] 
[at_gear_list use=rock-climbing]
[at_gear_list use="rock-climbing"]
[at_gear_list use='rock-climbing']
```

### at_faq
Параметры
- `toc=1` вывод оглавления перед Faq
- `toc=0` без оглавления
- `limit=-1` все записи
- `limit=10` 10 записей
- `category=main-set` ограничивает вывод записями с категорией `main-set`
- [at_faq] - по умолчанию выводит содержание и все записи
```
[at_faq] 
[at_faq toc=1 limit=-1 category=slug]
[at_faq toc=0 limit=10]
[at_faq use='rock-climbing']
```
