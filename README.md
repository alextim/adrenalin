## Общие правила оформления материалов (все типы постов)
### Заголовок
- уникальный, т.е. не должно быть второго такого заголовка на сайте
- одним предложением
- предложение недлинное
- стройте предложение так, чтобы не было запятых
- точку в конце не ставим
### Отрывок
Два, максимум три, предложения, кратко описывающие публикуемый материал.
### Изображение записи
Файл должен иметь осмысленное название, отражающее содержание картинки, и состоящее из ключевых слов для SEO.

Например:
```
montblanc-summit-ridge-view.jpg
```

Картинку до загрузки готовим в графическом редакторе
- разрешение: 800 x 500
- формат: jpg
- сохраняем для  WEB c качеством medium или ниже
## All In One SEO Pack
### Заголовок
- копируем текст "Заголовка" материала
- если длина текста больше 60 символов, то творчески сокращаем
- после сокращения текст должен остаться уникальным
### Описание
- копируем текст "Отрывка" материала
- если длина текста больше 160 символов, то творчески сокращаем
- "Описпние" должно быть уникальным
### Ключевые слова
Можно не заполнять. Google их игнорирует.


## Тип поста: FAQ
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

## Тип поста: Снаряжение

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
## Тип поста: Путешествия
### Прайс-лист

#### Оформление: классы CSS
- price-list-wrap





