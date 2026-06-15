##Работа с Git:
  1. Ничего не комитим и не пушим в master
  2. Для каждой фичи создаем отдельную ветку
     2.1 git status для проверки на какой ветке находимся
     2.2 git branch <название ветки> для создания новой ветки
     2.3 git checkout <название ветки> для переключения на ветку
     2.4 git push -u origin <название ветки> для публикации ветки в удаленном репозитории
  3. Для сливания ветки в master создаем pull request во вкладке на Github
  4. Ветки не удаляем
##Установка проекта
  1. git clone https://github.com/DAERRT/Schedule_TIU
  2. npm install
  3. composer install
  4. cp .env.example .env
  5. в .env в строчке APP_URL пишем APP_URL=http://localhost:8000
  6. php artisan migrate --seed
  7. php artisan key:generate
  8. compser run dev для запуска проекта
