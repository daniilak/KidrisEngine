<section class="content">
	{table}
  <div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#tab_1" data-toggle="tab">Студенты</a></li>
      <!-- <li><a href="#tab_2" data-toggle="tab">Факультеты</a></li> -->
      <li><a href="#tab_3" data-toggle="tab">Конференции</a></li>
      <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear fa-spin"></i></a></li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane active" id="tab_1">
        <div class="form-group">
          <input type="text" class="form-control boxed num_student" placeholder="Номер зачетки">
        </div>
        <div class="form-group">

          <input type="text" class="form-control boxed f_name_student" placeholder="Фамилия">
        </div>
        <div class="form-group">
          <button type="button" class="btn btn-success getUserInfo">Получить сведения</button>
        </div>
        <ul class="list-group">
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <strong>Фамилия </strong>
            <span class="badge badge-primary badge-pill fam"></span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <strong>Имя </strong>
            <span class="badge badge-primary badge-pill nam"></span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <strong>Отчество </strong>
            <span class="badge badge-primary badge-pill oth"></span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <strong>ФИО </strong>
            <span class="badge badge-primary badge-pill fam-nam-oth"></span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <strong>Курс </strong>
            <span class="badge badge-primary badge-pill course"></span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <strong>Факультет </strong>
            <span class="badge badge-primary badge-pill faculty"></span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <strong>Группа </strong>
            <span class="badge badge-primary badge-pill groupname"></span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <strong>Тип обучения </strong>
            <span class="badge badge-primary badge-pill level"></span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <strong>Пол </strong>
            <span class="badge badge-primary badge-pill sex"></span>
          </li>
        </ul>
      </div>
      <!-- <div class="tab-pane" id="tab_2">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Факультет</th>
              <th>Кол-во студентов</th>
              <th>Кол-во преподавателей</th>
              <th>Декан</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
      </div> -->
      <div class="tab-pane" id="tab_3">
          {list_events}
      </div>
    </div>
  </div>
</section>