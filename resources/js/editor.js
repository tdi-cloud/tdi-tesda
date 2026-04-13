import { Editor } from '@tiptap/core'
import StarterKit from '@tiptap/starter-kit'
import Underline from '@tiptap/extension-underline'
import TextAlign from '@tiptap/extension-text-align'
import { Indent }  from './extensions/Indent'

window.TiptapEditor = function (selector, input, content = '') {

    const el = document.querySelector(selector)
    const inputEl = document.querySelector(input)

    if (!el) {
        console.error('Editor element not found')
        return
    }

    const editor = new Editor({
        element: el,

        extensions: [
            StarterKit.configure({
                heading: {
                    levels: [1, 2, 3], // ✅ enable H1 H2 H3
                },
            }),
            Underline,
            TextAlign.configure({
                types: ['heading', 'paragraph'],
            }),
            Indent
        ],

        content: content,

        editable: true,

        onCreate({ editor }) {
            if (inputEl) inputEl.value = editor.getHTML()
        },

        onUpdate({ editor }) {
            document.querySelector('#content').value = editor.getHTML()
        },
    })

    window.editor = editor
    return editor
}