<div class="panel panel-default">
    <div class="panel-heading">
        Новая рецензия
    </div>
    <div class='panel-body'>
        <form class="form-horizontal" method="POST" action="{{ route('review.create', ['id' => $book->id]) }}">
            {{ csrf_field() }}

            <div class="form-group{{ $errors->has('rating') ? ' has-error' : '' }}">
                <label for="rating" class="col-md-4 control-label">Оценка</label>

                <div class="col-md-6">
                    <select id="rating" class="form-control" name="rating">
                        <option value="1"
                                {{ old('rating') == 1 ? 'selected' : '' }} >
                            1
                        </option>
                        <option value="2"
                                {{ old('rating') == 2 ? 'selected' : '' }} >
                            2
                        </option>
                        <option value="3"
                                {{ old('rating') == 3 ? 'selected' : '' }} >
                            3
                        </option>
                        <option value="4"
                                {{ old('rating') == 4 ? 'selected' : '' }} >
                            4
                        </option>
                        <option value="5"
                                {{ old('rating') == 5 ? 'selected' : '' }} >
                            5
                        </option>
                        <option value="6"
                                {{ old('rating') == 6 ? 'selected' : '' }} >
                            6
                        </option>
                        <option value="7"
                                {{ old('rating') == 7 ? 'selected' : '' }} >
                            7
                        </option>
                        <option value="8"
                                {{ old('rating') == 8 ? 'selected' : '' }} >
                            8
                        </option>
                        <option value="9"
                                {{ old('rating') == 9 ? 'selected' : '' }} >
                            9
                        </option>
                        <option value="10"
                                {{ old('rating') == 10 ? 'selected' : '' }} >
                            10
                        </option>
                    </select>

                    @if ($errors->has('rating'))
                        <span class="help-block">
                            <strong>{{ $errors->first('rating') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('text') ? ' has-error' : '' }}">
                <label for="text" class="col-md-4 control-label">Текст</label>

                <div class="col-md-6">
                    <textarea id="text" class="form-control review-edit-textarea" name="text" rows="5">{{ old('text') }}
                    </textarea>
                    @if ($errors->has('text'))
                        <span class="help-block">
                            <strong>{{ $errors->first('text') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                    <button type="submit" class="btn btn-primary">
                        Отправить
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>