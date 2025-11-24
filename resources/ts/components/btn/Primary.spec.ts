import Primary from '@/components/btn/Primary.vue'
import { render, screen } from '@testing-library/vue'
import { expect, it } from 'vitest'

it('should render with default Link tag prop', () => {
  render(Primary, {
    slots: {
      default: 'Click me',
    },
  })

  const link = screen.getByRole('link', { name: 'Click me' })

  expect(link).toBeDefined()
  expect(link.className).toContain('btn-primary')
})

it('should render with custom tag prop', () => {
  const { container } = render(Primary, {
    props: {
      tag: 'button',
    },
    slots: {
      default: 'Submit',
    },
  })

  const button = screen.getByRole('button', { name: 'Submit' })

  expect(button).toBeDefined()
  expect(button.className).toContain('btn-primary')
  expect(container.querySelector('button')).toBeDefined()
})

it('should render with custom tag prop as string', () => {
  render(Primary, {
    props: {
      tag: 'div',
    },
    slots: {
      default: 'Div Content',
    },
  })

  const element = screen.getByText('Div Content')

  expect(element).toBeDefined()
  expect(element.className).toContain('btn-primary')
  expect(element.tagName.toLowerCase()).toBe('div')
})

it('should apply btn-primary class from props', () => {
  render(Primary, {
    slots: {
      default: 'Button Text',
    },
  })

  const element = screen.getByText('Button Text')

  expect(element.className).toContain('btn-primary')
})

it('should render slot content', () => {
  render(Primary, {
    slots: {
      default: 'Custom Slot Content',
    },
  })

  const content = screen.getByText('Custom Slot Content')

  expect(content).toBeDefined()
})
