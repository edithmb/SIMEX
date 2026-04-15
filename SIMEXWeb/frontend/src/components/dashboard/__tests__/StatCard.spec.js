import { mount } from '@vue/test-utils'
import StatCard from '@/components/dashboard/StatCard.vue'

describe('StatCard', () => {
  it('renders title and value', () => {
    const wrapper = mount(StatCard, {
      props: { title: 'Envios', value: '142' },
    })
    expect(wrapper.text()).toContain('Envios')
    expect(wrapper.text()).toContain('142')
  })

  it('renders the truck SVG when icon is "truck"', () => {
    const wrapper = mount(StatCard, {
      props: { title: 'T', value: '1', icon: 'truck' },
    })
    expect(wrapper.find('.stat-card-icon svg').exists()).toBe(true)
    expect(wrapper.findAll('.stat-card-icon circle')).toHaveLength(2)
  })

  it('renders money SVG when icon is "money"', () => {
    const wrapper = mount(StatCard, {
      props: { title: 'T', value: '1', icon: 'money' },
    })
    expect(wrapper.find('.stat-card-icon svg').exists()).toBe(true)
  })

  it('renders offers SVG when icon is "offers"', () => {
    const wrapper = mount(StatCard, {
      props: { title: 'T', value: '1', icon: 'offers' },
    })
    expect(wrapper.find('.stat-card-icon svg').exists()).toBe(true)
  })

  it('renders check SVG when icon is "check"', () => {
    const wrapper = mount(StatCard, {
      props: { title: 'T', value: '1', icon: 'check' },
    })
    expect(wrapper.find('.stat-card-icon svg').exists()).toBe(true)
  })

  it('renders no SVG when icon is unrecognized', () => {
    const wrapper = mount(StatCard, {
      props: { title: 'T', value: '1', icon: 'unknown' },
    })
    expect(wrapper.find('.stat-card-icon svg').exists()).toBe(false)
  })

  it('hides trend section when trend prop is empty', () => {
    const wrapper = mount(StatCard, {
      props: { title: 'T', value: '1' },
    })
    expect(wrapper.find('.stat-card-trend').exists()).toBe(false)
  })

  it('shows trend with up direction', () => {
    const wrapper = mount(StatCard, {
      props: { title: 'T', value: '1', trend: '+12%', trendDirection: 'up' },
    })
    expect(wrapper.find('.stat-card-trend').exists()).toBe(true)
    expect(wrapper.find('.stat-card-trend-value--up').exists()).toBe(true)
    expect(wrapper.text()).toContain('+12%')
    expect(wrapper.text()).toContain('vs mes anterior')
  })

  it('shows trend with down direction', () => {
    const wrapper = mount(StatCard, {
      props: { title: 'T', value: '1', trend: '-5%', trendDirection: 'down' },
    })
    expect(wrapper.find('.stat-card-trend-value--down').exists()).toBe(true)
  })

  it('renders custom trendLabel', () => {
    const wrapper = mount(StatCard, {
      props: { title: 'T', value: '1', trend: '+3%', trendLabel: 'vs semana' },
    })
    expect(wrapper.text()).toContain('vs semana')
  })
})
