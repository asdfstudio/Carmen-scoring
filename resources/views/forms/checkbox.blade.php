@php if ($showLabel && $showField): @endphp
    @php if ($options['wrapper'] !== false): @endphp
    <div @php $options['wrapperAttrs'] @endphp >
    @php endif; @endphp
@php endif; @endphp

@php if ($showField): @endphp
    @php Form::checkbox($name, $options['value'], $options['checked'], $options['attr']) @endphp

    @php include 'help_block.php' @endphp
@php endif; @endphp

@php if ($showLabel && $options['label'] !== false && $options['label_show']): @endphp
    @php if ($options['is_child']): @endphp
        <label @php $options['labelAttrs'] @endphp>@php $options['label'] @endphp</label>
    @php else: @endphp
        @php Form::label($name, $options['label'], $options['label_attr']) @endphp
    @php endif; @endphp
@php endif; @endphp

@php include 'errors.php' @endphp

@php if ($showLabel && $showField): @endphp
    @php if ($options['wrapper'] !== false): @endphp
    </div>
    @php endif; @endphp
@php endif; @endphp
