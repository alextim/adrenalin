# Общие правила оформления материалов (все типы постов)
## Иностранные слова и названия
Первое упоминание на русском и сразу в скобках на латиннице оригинальное название. В дальнейшем в тексте только на русском.
Например:
```
Подъем на канатной дороге на вершину Эгюй дю Миди (Aiguille du Midi), 3842 м.
На самой вершине Эгюй дю Миди есть кафе.

```

## Перечисления и списки
Не создавайте списки вручную при поможи тире/цифры и превода строки.

Неправильно:
```
- текст 
- текст
- текст

1. Текст
2. Текст
2.1. Текст
2.2. Текст
3. Текст
```

Правильно:
```
<ul>
<li>текст</li>
<li>текст</li>
<li>текст</li>
</ul>
```
Результат:
<ul>
<li>текст</li>
<li>текст</li>
<li>текст</li>
</ul>

```
<ol>
<li>Текст</li>
<li>Текст
<ol>Текст
<li>Текст</li>
<li>Текст</li>
</ol>
</li>
<li>Текст</li>
</ol>
```
Результат:
<ol>
<li>Текст</li>
<li>Текст
<ol>Текст
<li>Текст</li>
<li>Текст</li>
</ol>
</li>
<li>Текст</li>
</ol>


## Поля
### Поле "Заголовок"
- уникальный, т.е. не должно существовать второго такого же заголовка на всем сайте
- одним предложением
- предложение не длинное
- по возможности стройте предложение так, чтобы не было запятых
- точку в конце не ставим

### Поле "Постоянная ссылка"
Перевод поля "Заголовок" на английский язык. Пробелы между словами, знаки препинания заменяем на тире `-`.
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
- поле "Атрибут alt" - одно предложение без точки в конце

:bulb: Тексты поля "Подпись" и  поля "Атрибут alt" должны отличаться..

## All In One SEO Pack
### Поле "Заголовок"
- должно соответствовать содержимому текста "Заголовка" материала
- если длина текста больше 60-70 символов, то творчески сокращаем
- после сокращения текст должен остаться уникальным, т.е. на сайте не должно быть второго материала с таким же заголовком

### Поле "Описание"
- краткий текст, характеризующий материал с ключевыми словами в содержимом
- если длина текста больше 160 символов, то творчески сокращаем
- "Описание" должно быть уникальным

### Поле "Ключевые слова"
Можно не заполнять. Google их игнорирует.


 :bulb: Увидеть, как найденный материал будет отображен поисковиком в браузере клиента, можно в поле "Предосмотр сниппета". Желательно, что бы Поле "Заголовок" материла и Поле "Заголовок" All In One SEO Pack отличались

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

## Тип поста: Путешествия
### Прайс-лист

#### Оформление: классы CSS
- price-list-wrap

# Home Page
## Слайдер
Картинки 1920 x 765

# Путешествие
## Поле "Изображение записи"
Картинка с соотношением сторон 4 x 3.

Например:
- 800 x 600
- 1000 x 750
- 1200 x 900
- 1333 x 1000
## Раздел "Программа по дням"
Картинки
- одно изображение - до 1600px в ширину
- два изображения  - одинаковая высота,  и в сумме по горизонтали 1600 px

:bulb: Хорошо смотрятся две картинки 800 x 500

## Раздел "Стоимость"
### Включено и Не включено
Оформляем в виде ненумерованного списка
```
<ul>
<li>Канатная дорога Скайвей (6-й день программы - 1 подъем, 7-й день - 1 спуск)</li>
<li>Прокат 2-х местной палатки в Курмайоре (если Вы решите жить в кемпинге)</li>
<li>Прокат газовой горелки и посуды для готовки</li>
<li>Пользование общественным альпинистским снаряжением (веревка, карабины, оттяжки, закладки и пр.)</li>
</ul> 
```
Начинаем с заглавной буквы, в конце точку не ставим.

## Раздел "Снаряжение"
Шорткод at_gear_list
Выводит список снаряжения, отсортированный по полю Sort Order из  таксономии "gear_type"

`use` - необязательный параметр для фильтрации продуктов по slug-у терма из таксономии "recommended_use".

Без `use` будут выведены все имеющиеся записи.

Пример: вывод на экран снаряжения для использования в "Гранитах Шамони". Можно с кавычками и без.
```
[at_gear_list use=granity-shamoni]
[at_gear_list use="granity-shamoni"]
[at_gear_list use='granity-shamoni']
```




