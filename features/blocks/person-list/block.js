(function(wpBlockEditor){

  const {registerBlockType} = wpBlockEditor.blocks;
  const {createElement, Fragment, useState} = wpBlockEditor.element;
	const {useBlockProps, InspectorControls} = wpBlockEditor.blockEditor;
	const {__} = wpBlockEditor.i18n;
	const compose = wpBlockEditor.compose;
	const ServerSideRender = wpBlockEditor.serverSideRender;
	const {withSelect} = wpBlockEditor.data;
  const {
    Button,
    Dashicon,
    SelectControl,
    PanelBody,
    PanelRow
  } = wpBlockEditor.components;

  const PersonSelector = compose.compose(
      withSelect((select, props) => {
        let params = {
          per_page: 100,
          status: 'publish',
        };

        if (props.languagesList) {
          let selectedLang = props.languagesList.querySelector('[selected]');

          if (selectedLang) {
            params.lang = selectedLang.value;
          }
        }

        return {
          people: select('core').getEntityRecords('postType', 'person', params),
        };
      })
    )(props => {
      if (! props.people) {
        return createElement('p', {}, __('Loading...', 'helsinki-sovittelutoiminta-core'));
      }

      const [selectedPerson, selectPerson] = useState(null);

      const {attributes, setAttributes} = props;
      const {persons} = attributes;

      const addPerson = (id) => setAttributes({persons: [...persons, id]});

      const removePerson = (id) => setAttributes({
        persons: persons.filter(postId => postId !== id)
      });

      const movePerson = (from, to) => {
        let ids = [...persons];

        ids.splice(to, 0, ids.splice(from, 1)[0]);

        return ids;
      };

      const movePersonUp = (id, index) => (index > 0) && setAttributes({
        persons: movePerson(index, index-1)
      });

      const movePersonDown = (id, index) => (index < persons.length - 1) && setAttributes({
        persons: movePerson(index, index+1)
      });

      const personOptions = [{
        value: '',
        label: __('Select', 'helsinki-sovittelutoiminta-core'),
      }];

      props.people.forEach(personPost => {
        if (! persons.includes(personPost.id)) {
          personOptions.push({
            value: personPost.id,
            label: personPost.title.rendered,
          })
        }
      });

      return createElement(Fragment, {},
        createElement('div', {className: 'person-selector'},
          createElement(SelectControl, {
            className: 'person-selector__dropdown',
            label: __('Person', 'helsinki-sovittelutoiminta-core'),
            options: personOptions,
            value: selectedPerson,
            onChange: personPostId => selectPerson(personPostId),
          }),
          createElement(Button, {
            className: 'person-selector__add',
            variant: 'primary',
            isSmall: true,
            onClick: () => selectedPerson
              ? addPerson(parseInt(selectedPerson, 10))
              : null,
          }, __('Add', 'helsinki-sovittelutoiminta-core'))
        ),
        createElement('ul', {className: 'people'},
          persons.map((postId, index) => {
            const person = props.people.find(personPost => personPost.id === postId);
            if (! person) {
              return;
            }

            return createElement('li', {className: 'people__item'},
              createElement('span', {className: 'people__reorder'},
                (index > 0) && createElement(Button, {
                  className: 'button',
                  size: 'compact',
                  onClick: () => movePersonUp(postId, index),
                }, createElement(Dashicon, {icon: 'arrow-up'})),
                (index < persons.length - 1) && createElement(Button, {
                  className: 'button',
                  size: 'compact',
                  onClick: () => movePersonDown(postId, index),
                }, createElement(Dashicon, {icon: 'arrow-down'})),
              ),
              createElement('span', {className: 'people__item__name'}, person.title.rendered),
              createElement(Button, {
                className: 'people__item__remove',
                variant: 'link',
                size: 'small',
                isDestructive: true,
                onClick: () => removePerson(postId),
              }, __('Remove', 'helsinki-sovittelutoiminta-core'))
            )
          })
        )
      );
    });

  function layoutOptions() {
    return [
      {value: 'horizontal', label: __('Horizontal', 'helsinki-sovittelutoiminta-core')},
      {value: 'vertical', label: __('Vertical', 'helsinki-sovittelutoiminta-core')}
    ];
  }

  function inspectorControls(controls) {
    return createElement(InspectorControls, {},
      createElement(PanelBody, {
          title: __('Settings', 'helsinki-sovittelutoiminta-core'),
          initialOpen: true
        },
        controls.map(block => createElement(PanelRow, {}, block))
      )
    );
  }

  function blockSettings(props) {
    const {attributes, setAttributes} = props;
    const {data, layout, persons} = attributes;

    const clearLegacyData = (key) => {
      if (data.hasOwnProperty(key)) {
        let dataCopy = {...data};

        delete dataCopy[key];

        setAttributes({data: dataCopy});
      }
    }

    return inspectorControls([
      createElement(SelectControl, {
        label: __('Layout', 'helsinki-sovittelutoiminta-core'),
        options : layoutOptions(),
        onChange: value => {
          clearLegacyData('layout');
          setAttributes({layout: value});
        },
        value: layout,
      }),
    ]);
  }

  function renderBlock(props, languagesList) {
    return createElement(PersonSelector, {...props, languagesList});
  }

  function renderPreview(props) {
    return createElement(ServerSideRender, {
      block: 'acf/person-list',
      attributes: {...props.attributes}
    });
  }

  function edit(props) {
    const {isSelected} = props;
    const languagesList = document.querySelector('.post_lang_choice');

    return createElement(Fragment, {},
      blockSettings(props),
      createElement('div', useBlockProps(), isSelected ? renderBlock(props, languagesList) : renderPreview(props))
    );
  }

  registerBlockType('acf/person-list', {
    title: __( 'Person list', 'helsinki-sovittelutoiminta-core' ),
    edit: edit,
  });

})(window.wp);

function mapLegacyPersonListAcfData(block, blockType, innerHTML) {
  if (blockType.name == 'acf/person-list') {

    if ( ! block.layout && block.data.layout ) {
      block.layout = block.data.layout;
    }

    if ( block.persons.length === 0 && block.data.persons ) {
      block.persons = block.data.persons.map(id => parseInt(id, 10));
    }
  }

  return block;
}
wp.hooks.addFilter(
  'blocks.getBlockAttributes',
  'helsinki-sovittelutoiminta-core/person-list-data-mapping',
  mapLegacyPersonListAcfData
);
