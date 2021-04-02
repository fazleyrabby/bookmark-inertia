import { Inertia } from '@inertiajs/inertia'
import React from 'react'
import Layout from '../../../components/common/layout'

interface Props {
    bookmark: any
}

const BookmarkViewPage: React.FC<Props> = ({ bookmark }) => {
    const handleSave = (event: React.MouseEvent) => {
        event.preventDefault()
        Inertia.post('/bookmark/make-active', {id: bookmark.id})
    }
    return (
        <Layout>
            <div className="row">
                <div className="col-md-6">
                    {bookmark.title && (
                        <div className="card">
                            <div className="card-header">{bookmark.title}</div>
                            <div className="card-body">
                                <p>URL: {bookmark.url}</p>
                                <p>{bookmark.description}</p>
                                <img width='100%' src={bookmark.img_url} alt={bookmark.title} />
                                
                                <button className={`btn btn-${!bookmark.is_active ? 'primary' : 'danger'} mt-4`} onClick={handleSave}>{bookmark.is_active ? 'Inactive' : 'Active'}</button>
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </Layout>
    )
}

export default BookmarkViewPage
