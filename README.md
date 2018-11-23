# Универсальная docker-обвязка для Yii2-проектов

Проект состоит из 3 глобальных директорий:
* docker (здесь хранится конфигурация всех контейнеров)
* app (в этой папке вы инициализируете новый Yii2-проект или перемещаете сюда старый)
* mysql (создается автоматически при первом запуске проекта)
* templates (папка с шаблонами, которые при автоматической инициализации раскидываются по правильным папкам)

Теперь активируй эту docker-обвязку, для этого запусти скрипт `./app_init.sh`.  

Для запуска проекта выполни команду `docker-compose up`

Скрипт `update.sh` нужен, чтобы стянуть с удаленного репозитория обновления и накатить новые миграции.