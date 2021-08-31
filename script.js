const DataOperator = function () {
  this.delete = function (event) {
    event.preventDefault();
    let task_item = this.parentElement.parentElement;
    let id = task_item.getAttribute('data-id');

    request.get('api.php?delete=' + id + '&page=' + PAGE, function (data) {
      console.log(data);
      delete TASKS[id];
      task_item.remove();
    });
  };

  this.addNew = function (response, form) {
    let res = JSON.parse(response);

    if (res.status === true) {
      TASKS[res.id] = {
        text: res.task.text,
        status: false,
      };
      form.querySelector('input').value = '';

      displayTask(res.id, res.task);
    }
  };

  for (const [id, task] of Object.entries(TASKS)) {
    displayTask(id, task);
  }

  function displayTask(id, task) {
    let tasks = document.querySelector('#task_list'),
      template = tasks.querySelector('.template'),
      new_task = template.cloneNode(true);
    new_task.classList.remove('template');

    let description = new_task.querySelector('.todo__description');
    description.textContent = task.text;
    if (task.status) {
      new_task.querySelector('.done').checked = task.status;
      new_task.querySelector('.done').setAttribute('checked', task.status);
    }
    new_task.setAttribute('data-id', id);

    new_task
      .querySelector('.todo__edit')
      .addEventListener('click', function (event) {
        event.preventDefault();
        let new_input = document.createElement('input');

        new_input.setAttribute('type', 'text');
        new_input.classList.add('todo__edit-input');
        new_input.value = task.text;
        new_task.classList.remove('todo__item--initial');
        new_task.classList.add('todo__item--edit');
        description.after(new_input);
      });

    new_task
      .querySelector('.todo__cancel')
      .addEventListener('click', function (event) {
        event.preventDefault();

        new_task.classList.remove('todo__item--edit');
        new_task.classList.add('todo__item--initial');
        new_task.querySelector('.todo__edit-input').remove();
      });

    new_task
      .querySelector('.todo__save')
      .addEventListener('click', function (event) {
        event.preventDefault();

        let new_text = new_task.querySelector('.todo__edit-input').value;
        let url =
          'api.php?update=' +
          id +
          '&page=' +
          PAGE +
          '&new_text=' +
          encodeURIComponent(new_text);
        request.get(url, function (data) {
          TASKS[id].text = data.task;
          description.textContent = data.task;

          new_task.classList.remove('todo__item--edit');
          new_task.classList.add('todo__item--initial');
          new_task.querySelector('.todo__edit-input').remove();
        });
      });

    tasks.prepend(new_task);
  }

  this.taskDone = function () {
    let done = this.checked;

    let task_item = this.parentElement;
    let id = task_item.getAttribute('data-id');

    let url =
      'api.php?update=' +
      id +
      '&page=' +
      PAGE +
      '&done=' +
      encodeURIComponent(done);
    request.get(url, function (data) {
      console.log(data);
    });

    this.setAttribute('checked', done);
  };
};

const tasks_manager = new DataOperator();
