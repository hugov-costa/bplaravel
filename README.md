readme
## Laravel API Boilerplate
This is a Laravel API boilerplate using Docker/Sail still in early development. I aim to make an API developing faster and easier, skipping most of the user account management, as well as authentication. At first, this is a project made for personal use, but feel free to use, change and, well, really do anything you want with it!

## Install dependencies and run application

- Install Docker;
- Run the following commands to both install dependencies and sail:
    ```
    docker build -t image-name . --no-cache
    docker run --rm -v $(pwd):/var/www/html image-name
    ```
- If necessary, grant permissions to your user:
    ```
    sudo chown -R $(whoami):$(whoami) .
    ```
- Run the following command to build (built only once) and run the application:
    ```
    ./vendor/bin/sail up -d
    ```
- Generate the application key:
    ```
    ./vendor/bin/sail artisan key:generate
    ```
- Migrate the database tables:
    ```
    ./vendor/bin/sail artisan migrate
    ```

### Optional
Set Docker to run on boot and create an alias to sail (change bash to zsh if applicable).

- Open .bashrc:
    ```
    nano ~/.bashrc
    ```
- Add the following lines to the file and save it:
    ```
    sudo service docker start
    alias sail='./vendor/bin/sail’
    ```
- Save the changes permanently:
    ```
	source ~/.bashrc
    ```