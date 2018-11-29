## FAQ
### Оформление: классы CSS
- faq
- faq-questions
- faq-answers


Ко всем вопросам добавлен аттрибут href с post_id 
```
href="#answer-{post_id}"

```


Ко всем ответам добавлен аттрибут id с post_id 
```
id="answer-{post_id}"

```

### Вывод на экран
Шорткод at_faq

Параметры
- `toc=1` вывод оглавления - список всех вопросов
- `toc=0` без оглавления
- `limit=-1` все записи
- `limit=10` 10 записей
- `category=main-set` ограничивает вывод записями с категорией `main-set`
- `[at_faq]` - без параметров выводит оглавление и все записи
```
[at_faq] 
[at_faq toc=1 limit=-1 category=slug]
[at_faq toc=0 limit=10]
[at_faq use='rock-climbing']
```

## Снаряжение
### Оформление: классы CSS
- gear-list
### Вывод на экран
Шорткод at_gear_list
Выводит список снаряжения, отсортированный по полю Sort Order из  таксономии "gear_type"

`use` - необязательный параметр для фильтрации продуктов по slug-у терма из таксономии "recommended_use".

Без `use` будут выведены все имеющиеся записи.
```
[at_gear_list] 
[at_gear_list use=rock-climbing]
[at_gear_list use="rock-climbing"]
[at_gear_list use='rock-climbing']
```
## Путешествия
### Прайс-лист

#### Оформление: классы CSS
- price-list-wrap
### форма Регистрации 
Для использования встроенной формы Регистрации из плагина "AT Contact Form" переопределите константу в плагине AT Trip (Путешествия) в файле `at-trip.php`.
```
define ('AT_TRIP_USE_GOOGLE_FORMS', false);
```




