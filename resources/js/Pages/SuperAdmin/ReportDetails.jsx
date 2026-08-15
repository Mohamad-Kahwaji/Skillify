import SuperAdminLayout from '../../Layouts/SuperAdminLayout';
import ReportDetailsView from '../../Components/ReportDetails';

export default function ReportDetails(props) {
    return <ReportDetailsView {...props} Layout={SuperAdminLayout} backHref="/super-admin/reports" actionPrefix="/super-admin/reports" />;
}
