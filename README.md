# Общие правила оформления материалов (все типы постов)
## Длина текста
Количество слов | Поведение поисковика
менее 500 <  |  не инедксирует Яндекс
более 1800  | ТОП-10 Google

## Иностранные слова и названия
Первое упоминание на русском и сразу в скобках на латиннице оригинальное название. В дальнейшем в тексте только на русском.
Например:
````
Подъем на канатной дороге на вершину Эгюй дю Миди (Aiguille du Midi), 3842 м.
На самой вершине Эгюй дю Миди есть кафе.
````
## Ссылки
### Внутренние ссылки
Это ссылки на ресурсы внутри сайта: посты, страницы, изображения.

Их необходимо делать относительными, а не абсолютными.

Неправильно:
```html
<a href="https://travel.adrenalin.od.ua/trips/hoverla-petros-climb-light-no-backpack/"Восхождение на Говерлу</a>
```

Правильно:
```html
<a href="/trips/hoverla-petros-climb-light-no-backpack/"Восхождение на Говерлу</a>
```

### Внешние ссылки
Абсолютные ссылки на сторонние сайты и ресурсы.

Чужие ресурсы не должны индексироваться поисковиком через наш сайт.

Чтобы это не происходило нужно к ссылке добавить аттрибуты `target="_blank" rel="noopener nofollow"`.


Неправильно:
```html
<a href="https://alp.od.ua/">Альпклуб</a>
```

Правильно:
```html
<a target="_blank" rel="noopener nofollow" href="https://alp.od.ua/">Альпклуб</a>
```

## Перечисления и списки
Не создавайте ламерские списки при помощи тире/цифры и перевода строки.

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
```html
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

```html
<ol>
  <li>Текст</li>
  <li>Текст
     <ol>
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
<ol>
<li>Текст</li>
<li>Текст</li>
</ol>
</li>
<li>Текст</li>
</ol>

:bulb: Отступы только для наглядности. Форматирование задается централизованно внешними стилями.
# Инструменты для проверки
- https://glvrd.ru/
- https://kparser.com
- https://copywritely.com
- https://sitechecker.pro
- https://a.pr-cy.ru/tools/content/

# Поля
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

Неправильно:
```
DSC0123200419.jpg
```

Правильно:
```
montblanc-summit-ridge-view.jpg
```

Картинку предварительно обрабатываем в графическом редакторе (Photoshop)
- размер Ш x В: 800px x 600px
- формат: jpg
- сохраняем для WEB c качеством medium или ниже (30%)

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

# Home Page
## Слайдер
Картинки 1920 x 765

# Тип поста: Путешествия 
## Поле "Изображение записи"
Картинка с соотношением сторон 4 x 3.

Например:
- 800 x 600
- 1000 x 750

## Раздел "Программа по дням"
### Детализация программы по дням
#### Заголовок дня
- с заглавной буквы
- одним предложением
- без точки в конце

Неправильно:
```
Восхождение на Говерлу. 2061 метр.
```

Правильно:
```
Восхождение на Говерлу, 2061 м
```


#### Текст
Длинный текст разбивайте на параграфы при помощи тега `p`
#### Картинки
Может быть до двух картинок на день
- одно изображение - до 1600px в ширину
- два изображения  - одинаковая высота,  и в сумме по горизонтали 1600 px

:bulb: Хорошо смотрятся две картинки рядом 800 x 500 или одна панорама 1600 x 400

## Раздел "Стоимость"
### Поля "Включено" и "Не включено"
- оформляем в виде ненумерованного списка
- с заглавной буквы 
- одним предложением 
- без точки в конце

Например:
```html
<ul>
<li>Канатная дорога Скайвей (6-й день программы - 1 подъем, 7-й день - 1 спуск)</li>
<li>Прокат 2-х местной палатки в Курмайоре (если Вы решите жить в кемпинге)</li>
<li>Прокат газовой горелки и посуды для готовки</li>
<li>Пользование общественным альпинистским снаряжением (веревка, карабины, оттяжки, закладки и пр.)</li>
</ul> 
```


## Раздел "Снаряжение"
Шорткод at_gear_list
Выводит список снаряжения, отсортированный по полю Sort Order из  таксономии "gear_type"

`use` - необязательный параметр для фильтрации продуктов по slug-у терма из таксономии "recommended_use".

Без `use` будут выведены все имеющиеся записи.

Пример: вывод на экран снаряжения для использования в "Гранитах Шамони". 

granity-shamoni - можно с кавычками и без.
```
[at_gear_list use=granity-shamoni]
[at_gear_list use="granity-shamoni"]
[at_gear_list use='granity-shamoni']
```

## Раздел "Галерея"
Не заполнять


