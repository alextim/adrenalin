## Общие правила оформления материалов (все типы постов)
### Поле "Заголовок"
- уникальный, т.е. не должно существовать второго такого же заголовка на всем сайте
- одним предложением
- предложение недлинное
- по возможности стройте предложение так, чтобы не было запятых
- точку в конце не ставим

### Поле "Постоянная ссылка"
Перевод поля "Заголовок" на английский язык. Пробелы между словами, знаки припинания заменяем на тире `-`.
### Поле "Отрывок"
Два, максимум три, предложения, кратко описывающие публикуемый материал.

### Поле "Изображение записи"
Файл должен иметь осмысленное название, отражающее содержание картинки, и состоящее из ключевых слов для SEO.

Например:
```
montblanc-summit-ridge-view.jpg
```

Картинку предварительно обрабатываем в графическом редакторе (Photoshop)
- размер Ш x В: 800px x 600px
- формат: jpg
- сохраняем для WEB c качеством medium или ниже

Во время загрузки на сайт заполняем:
- поле "Подпись" - одно-три предложения
- поле "Атрибут alt" - одно предложение в несколько слов без точки в конце

Тексты поля "Подпись" и  поля "Атрибут alt" должны отличаться.

## All In One SEO Pack
### Поле "Заголовок"
- копируем текст "Заголовка" материала
- если длина текста больше 60 символов, то творчески сокращаем
- после сокращения текст должен остаться уникальным

### Поле "Описание"
- копируем текст "Отрывка" материала
- если длина текста больше 160 символов, то творчески сокращаем
- "Описание" должно быть уникальным

### Поле "Ключевые слова"
Можно не заполнять. Google их игнорирует.


 :bulb: Увидеть, как найденный материал будет отображен поисковиком в браузере клиента, можно в поле "Предосмотр сниппета"

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





