@extends('backend.master')

@section('title')
    Create element
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('assets') }}/select2/css/select2.min.css">
@endsection

@section('content')
    <!-- Main section -->
    <main class="mt-5 pt-3">
        {{-- display error message --}}
        @if (Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert"
                style="
           margin: 10px 5px 10px 5px;">
                <strong>{{ Session::get('success') }}</strong>.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @elseif (Session::has('warning'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert"
                style="
           margin: 10px 5px 10px 5px;">
                <strong>{{ Session::get('warning') }}</strong>.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        {{-- //display error message --}}

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 fw-bold fs-3">
                    Elements
                </div>
            </div>
            <div class="row">
                <div class="row">
                    <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="float-start">Create element</h5>
                                <a href="{{ route('option-set.show', $option_set->id) }}" class="btn btn-sm btn-primary float-end mr-4 mt-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-list">
                                        <line x1="8" y1="6" x2="21" y2="6"></line>
                                        <line x1="8" y1="12" x2="21" y2="12"></line>
                                        <line x1="8" y1="18" x2="21" y2="18"></line>
                                        <line x1="3" y1="6" x2="3.01" y2="6"></line>
                                        <line x1="3" y1="12" x2="3.01" y2="12"></line>
                                        <line x1="3" y1="18" x2="3.01" y2="18"></line>
                                    </svg>
                                    Elements list
                                </a>

                            </div>
                            <div class="card-body">
                                <form action="{{ route('element.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col">

                                            <div class="d-flex form-group justify-content-end mb-3">
                                                <button id="submit" type="submit"
                                                    class="btn btn-primary me-1 mt-3">Save</button>
                                            </div>

                                            <div class="col">
                                                <div class="form-check form-switch mb-3 mt-5">
                                                    <input class="form-check-input" checked value="1" name="status"
                                                        type="checkbox" role="switch" id="status">
                                                    <label class="form-check-label" for="status">Active</label>
                                                </div>
                                            </div>

                                            <input type="hidden" name="option_set_id" value="{{ $option_set->id }}">
                                            <div class="row">
                                                <div class="col-md-lg-7 col-md-7 col-sm-12">

                                                    <div class="col">
                                                        <div class="form-group mb-3">
                                                            <label for="label">Element type<sup style="color: red">(*)
                                                                </sup></label>
                                                            <select class="form-control" name="type" id="type">
                                                                <option value="text">Text</option>
                                                                <option value="select">Select</option>
                                                                <option value="textarea">Textarea</option>
                                                                <option value="dialog">Dialog</option>
                                                                <option value="fontpreview">Font preview</option>
                                                                <option value="imagepreview">Image preview</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <!-- Option values -->
                                                    <div id="optionGroup" class="col d-none">
                                                        <input type="hidden" id="option_value" name="option_value">

                                                        <div id="optionContainer">
                                                            <label for="option">Option values</label>
                                                        </div>

                                                        <button id="addOptionBtn" type="button"
                                                            class="btn btn-sm btn-primary mb-3">
                                                            Add another option
                                                        </button>
                                                    </div>
                                                    <!-- end option values -->


                                                    <!-- <div class="col">
                                            <div class="form-group mb-3">
                                                <label for="label">Element label<sup style="color: red">(*) </sup></label>
                                                <input id="label" type="text" class="form-control" name="label" value="{{ old('label') }}" />
                                            </div>
                                        </div> -->
                                                    <div class="col">
                                                        <div class="form-group mb-3">
                                                            <label for="name">Element name<sup style="color: red">(*)
                                                                </sup></label>
                                                            <input id="name" type="text" class="form-control"
                                                                name="name" value="{{ old('name') }}" />
                                                        </div>
                                                    </div>

                                                    {{-- <div id="priceGroup" class="col">
                                    <div class="form-group mb-3">
                                        <label for="price">Price</label>
                                        <input id="price" type="number" class="form-control" name="price" value="{{ old('price',0.0) }}" />
                                    </div>
                                </div> --}}

                                                    <div id="limitGroup" class="col">
                                                        <div class="form-group mb-3">
                                                            <label for="limit">Limit character</label>
                                                            <input id="limit" type="number" class="form-control"
                                                                name="limit" value="{{ old('limit') }}" />
                                                        </div>
                                                    </div>

                                                    <!-- font preview -->
                                                    <div id="fontpreviewGroup" class="col">
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="col">
                                                                    <div class="form-group mb-3">
                                                                        <input id="color" value="#e8d2a0"
                                                                            type="color" name="color" />
                                                                        <label for="color">Color</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col">
                                                                    <div class="form-group mb-3">
                                                                        <label>Preview:</label>
                                                                        <input class="form-control" id="preview_text"
                                                                            type="text" />
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <script>
                                                            (function() {
                                                                const preview_text = document.querySelector('#preview_text');
                                                                const font_id = document.querySelector('#font_id');

                                                                const handleFontChange = () => {
                                                                    preview_text.style.fontFamily = font_id.options[font_id.selectedIndex].text;
                                                                }
                                                                handleFontChange();
                                                                font_id.addEventListener("change", function(e) {
                                                                    handleFontChange()
                                                                })

                                                            })()
                                                        </script>
                                                    </div>
                                                    <!-- end fontpreview -->



                                                    {{-- dialog --}}
                                                    <div id="dialogGroup" class="col">
                                                        <div class="col">
                                                            <div class="form-group mb-3">
                                                                <label for="dialog_title">Title<sup style="color: red">(*)
                                                                    </sup></label>
                                                                <input name="dialog_title" type="text"
                                                                    class="form-control form-control-sm" id="dialogTitle">
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="form-group mb-3">
                                                                <label for="dialog_body">body</label>
                                                                <textarea name="dialog_body" class="editor form-control" id="dialogBody" rows="3"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- end dialog --}}

                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="form-group mb-3">
                                                                <label for="placeholder">Placeholder<sup
                                                                        style="color: red">(*) </sup></label>
                                                                <textarea name="placeholder" class="form-control form-control-sm" id="placeholder" rows="3">{{ old('placeholder') }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="form-group mb-3">
                                                                <label for="help_text">Help text</label>
                                                                <textarea name="help_text" class="form-control form-control-sm" id="placeholder" rows="3">{{ old('help_text') }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- Condition logic --}}
                                                    <div id="conditionGroup" class="col">
                                                        <div class="form-check form-switch mt-5">
                                                            <input class="form-check-input" value="1"
                                                                name="is_condition" type="checkbox" role="switch"
                                                                id="is_condition">
                                                            <label class="form-check-label" for="is_condition">Condition
                                                                Logic Options</label>
                                                        </div>
                                                        <div id="conditionGroupOption">
                                                            <div class="form-group mb-3">
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <div class="form-group mb-3">
                                                                            <select disabled
                                                                                class="form-control form-control-sm"
                                                                                name="action" id="action">
                                                                                <option value="show">show</option>
                                                                                <option value="hide">hide</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    this field if
                                                                    <div class="col">
                                                                        <div class="form-group mb-3">
                                                                            <select disabled
                                                                                class="form-control form-control-sm"
                                                                                name="match" id="match">
                                                                                <option value="all">all</option>
                                                                                <option selected value="any">any
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div> of the following match:
                                                                </div>
                                                                <!-- Condition container -->
                                                                <input id="condition" type="hidden" class="form-control"
                                                                    name="condition" value="{{ old('condition') }}" />
                                                                <div id="conditionContainer"></div>

                                                                <!-- end condition container -->
                                                                <div class="col">
                                                                    <button id="addConditionBtn" type="button"
                                                                        class="btn btn-sm btn-primary mb-3">
                                                                        Add another option
                                                                    </button>
                                                                </div>

                                                            </div>
                                                        </div>

                                                    </div>
                                                    {{-- End condition logic --}}

                                                </div>
                                            </div>



                                            <script>
                                                /**
                                                 * Type option
                                                 */
                                                (function() {
                                                    const type = document.querySelector("#type");
                                                    // const priceGroup = document.querySelector("#priceGroup");
                                                    const limitGroup = document.querySelector("#limitGroup");
                                                    const optionGroup = document.querySelector("#optionGroup");
                                                    const fontpreviewGroup = document.querySelector("#fontpreviewGroup");

                                                    // dialog
                                                    const dialogGroup = document.querySelector("#dialogGroup");

                                                    function toggle() {
                                                        if (type.value == "select") { // select
                                                            // priceGroup.classList.add("d-none");
                                                            limitGroup.classList.add("d-none");
                                                            dialogGroup.classList.add("d-none");
                                                            fontpreviewGroup.classList.add("d-none");
                                                            optionGroup.classList.remove("d-none"); // show option
                                                            initOptions();
                                                        } else if (type.value == "dialog") {
                                                            limitGroup.classList.add("d-none");
                                                            optionGroup.classList.add("d-none");
                                                            fontpreviewGroup.classList.add("d-none");
                                                            dialogGroup.classList.remove("d-none");
                                                        } else if (type.value == "fontpreview") {
                                                            dialogGroup.classList.add("d-none");
                                                            optionGroup.classList.add("d-none");
                                                            limitGroup.classList.remove("d-none");
                                                            fontpreviewGroup.classList.remove("d-none");
                                                        } else { // text, textarea
                                                            // priceGroup.classList.remove("d-none");
                                                            dialogGroup.classList.add("d-none");
                                                            optionGroup.classList.add("d-none");
                                                            fontpreviewGroup.classList.add("d-none");
                                                            limitGroup.classList.remove("d-none");
                                                        }
                                                    }
                                                    toggle();
                                                    type.addEventListener("change", function(e) {
                                                        toggle();
                                                    })
                                                })()
                                                // genertae new option values field

                                                // get data from variant element and set into a hidden input element
                                                document.querySelector("#submit").addEventListener("click", save);

                                                function save(e) {
                                                    let data = []
                                                    const divs = document.querySelectorAll(".option-value")
                                                    for (const el of divs) {
                                                        let value = el.querySelector("#optionItem").value;
                                                        let price = el.querySelector("#optionItemPrice").value;
                                                        data.push({
                                                            price: price,
                                                            value: value,
                                                            text: value
                                                        });
                                                    }
                                                    const optionField = document.querySelector("#option_value")
                                                    optionField.value = JSON.stringify(data)
                                                    // save condition
                                                    saveCondition();
                                                }

                                                // remove option
                                                function remove(e) {
                                                    const divs = document.querySelectorAll(".option-value")
                                                    for (const el of divs) {
                                                        if (el.dataset.id == e) {
                                                            el.remove()
                                                            break;
                                                        }
                                                    }
                                                }

                                                // remove condition option
                                                function removeCondition(e) {
                                                    const divs = document.querySelectorAll(".condition-option")
                                                    for (const el of divs) {
                                                        if (el.dataset.id == e) {
                                                            el.remove()
                                                            break;
                                                        }
                                                    }
                                                }

                                                function initOptions() {
                                                    let id = 1;
                                                    const optionContainer = document.querySelector("#optionContainer")
                                                    const addOptionBtn = document.querySelector("#addOptionBtn")

                                                    function handleClick(e) {
                                                        const html = `
                                            <div class="row">
                                                <div class="col">
                                                    <input type="text" placeholder="value" id="optionItem" class="form-control" name="option" />
                                                </div>

                                                <div class="col">
                                                    <input type="number" placeholder="price" id="optionItemPrice" class="form-control" name="price" />
                                                </div>
                                            </div>
                                            
                                            <button onclick="remove(${id})" type="button" class="btn btn-sm btn-danger">Remove</button>
                                        `
                                                        const div = document.createElement("div");
                                                        div.className = "option-value form-group mb-3 col";
                                                        div.setAttribute("data-id", id);
                                                        div.innerHTML = html;

                                                        optionContainer.appendChild(div);
                                                        id++;
                                                    }
                                                    // add options value
                                                    addOptionBtn.addEventListener("click", handleClick)
                                                }

                                                /**
                                                 * Condition
                                                 */
                                                function saveCondition() {
                                                    let data = {
                                                        action: "",
                                                        match: "",
                                                        condition: []
                                                    }
                                                    const divs = document.querySelectorAll(".condition-option")
                                                    const action = document.querySelector('#action');
                                                    const match = document.querySelector('#match');
                                                    // set value
                                                    data.action = action.value;
                                                    data.match = match.value;
                                                    for (const el of divs) {

                                                        let element_name = el.querySelector("#element_name").value;
                                                        let condition = el.querySelector("#selectCondition").value;
                                                        let value = el.querySelector("#value").value;

                                                        data.condition.push({
                                                            element_name: element_name,
                                                            condition: condition,
                                                            value: value,
                                                        });
                                                    }
                                                    const optionField = document.querySelector("#condition")
                                                    optionField.value = JSON.stringify(data)
                                                }
                                                // more util
                                                function checkDropdown(e, valueId) {
                                                    const elements = <?php echo json_encode($elements); ?>;
                                                    const valueEl = document.querySelector(`#${valueId}`)
                                                    // console.log(valueEl);
                                                    // html
                                                    const textHtml = `<input class="form-control form-control-sm" type="text" name="value" id="value">`;
                                                    //
                                                    for (const element of elements) {
                                                        if (element.name == e.value) {
                                                            if (element.type == "select") {
                                                                const selectHtml = `
                                        <select class="form-control form-control-sm" name="value" id="value">
                                            ${element.option_value.map((optionValue)=>{
                                                return `
                                                                                                                                                                                    <option value="${optionValue.value}">${optionValue.text}</option>
                                                                                                                                                                                    `
                                            })}
                                        </select>
                                        `;
                                                                valueEl.innerHTML = selectHtml;
                                                            } else {
                                                                valueEl.innerHTML = textHtml;
                                                            }
                                                        }
                                                    }
                                                }
                                                //
                                                (function initConditions() {
                                                    let id = 1;
                                                    //condition
                                                    const is_condition = document.querySelector("#is_condition");
                                                    const conditionGroupOption = document.querySelector("#conditionGroupOption");
                                                    const addConditionBtn = document.querySelector("#addConditionBtn")
                                                    const conditionContainer = document.querySelector("#conditionContainer");

                                                    function toggleCondition() {
                                                        if (is_condition.checked == false) {
                                                            conditionGroupOption.classList.add("d-none");
                                                        } else { // select
                                                            conditionGroupOption.classList.remove("d-none"); // show condition
                                                        }
                                                    }
                                                    toggleCondition();
                                                    is_condition.addEventListener("change", function(e) {
                                                        toggleCondition();
                                                    })

                                                    function handleAddCondition() {
                                                        const html = `
                                            <div class="col">
                                                <div class="form-group mb-3">
                                                    <select onchange="checkDropdown(this,'value${id}')" class="form-control form-control-sm" name="element_name" id="element_name">
                                                        <option value="0">Select element</option>
                                                        @foreach ($elements as $element)
                                                        <option value="{{ $element->name }}">{{ $element->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group mb-3">
                                                    <select class="form-control form-control-sm" name="selectCondition" id="selectCondition">
                                                        <option value="equal">is equal to</option>
                                                        <option value="not_equal">is not equal to</option>
                                                        <option value="greater_than">is greater than</option>
                                                        <option value="less_than">is less than</option>
                                                        <option value="starts_with">starts with</option>
                                                        <option value="ends_with">ends with</option>
                                                        <option value="contains">contains</option>
                                                        <option value="not_contains">does not contain</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div id="value${id}" class="form-group mb-3">
                                                    <input class="form-control form-control-sm" type="text" name="value" id="value">
                                                </div>
                                                <button onclick="removeCondition(${id})" type="button" class="btn btn-sm btn-danger">Remove</button>
                                            </div>
                                        `

                                                        const div = document.createElement("div");
                                                        div.className = "row condition-option";
                                                        div.setAttribute("data-id", id);
                                                        div.innerHTML = html;

                                                        conditionContainer.appendChild(div);
                                                        id++;
                                                    }
                                                    // add options value
                                                    addConditionBtn.addEventListener("click", handleAddCondition)
                                                })()
                                            </script>




                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group mb-3">
                                                        <button id="submit" type="submit"
                                                            class="btn btn-primary mt-3">Save</button>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </main>
    <!-- End Main section -->
@endsection

@section('script')
    <script src="{{ asset('assets') }}/tinymce/tinymce.min.js"></script>
    <script src="{{ asset('assets') }}/select2/js/select2.min.js"></script>
    <script>
        // select2
        $(".select2").select2();
    </script>

    <script>
        // tinymce
        tinymce.init({
            selector: '#description',
        });
    </script>
    <script>
        const name = document.querySelector('#name');
        const slug = document.querySelector('#slug');
        name.addEventListener("keyup", function(e) {
            slug.value = replace(e.target.value.toLowerCase(), " ", "-")
        });
    </script>
@endsection
