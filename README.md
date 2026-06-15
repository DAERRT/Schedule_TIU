**Работа с Git:**

1. Ничего не комитим и не пушим в master<br>
2. Для каждой фичи создаем отдельную ветку<br>
    - git status для проверки на какой ветке находимся<br>
    - git branch <название ветки> для создания новой ветки<br>
    - git checkout <название ветки> для переключения на ветку<br>
    - git push -u origin <название ветки> для публикации ветки в удаленном репозитории<br>
3. Для сливания ветки в master создаем pull request во вкладке на Github<br>
4. Ветки не удаляем<br>
   **Установка проекта:**
5. git clone https://github.com/DAERRT/Schedule_TIU
6. npm install
7. composer install
8. cp .env.example .env
9. в .env в строчке APP_URL пишем APP_URL=http://localhost:8000
10. php artisan migrate --seed
11. php artisan key:generate
12. compser run dev для запуска проекта
