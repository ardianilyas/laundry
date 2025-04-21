import { usePage } from '@inertiajs/vue3';

export default function useRoles() {
  const { props } = usePage();

  const getRoles = () => {
    return props.auth?.roles ?? [];
  };

  const hasRole = (role) => {
    return props.auth?.roles.includes(role) ?? false;
  };

  const hasAnyRole = (roles) => {
    return roles.some((role) => props.auth?.roles.includes(role)) ?? false;
  };

  const hasAllRoles = (roles) => {
    return roles.every((role) => props.auth?.roles.includes(role)) ?? false;
  };

  return {
    getRoles,
    hasRole,
    hasAnyRole,
    hasAllRoles,
  };
}